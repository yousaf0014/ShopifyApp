<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use Auth;

use App\Order;
use App\OrderItem;
use App\OrderShipment;
use App\user;
use App\UserProdcutVariant;
use App\UserProduct;
use App\CountryCode;


class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        
    }
    
    public function index()
    {
        $orderObj =  new Order;
        $shop = $keyword = $status = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $orderObj = $orderObj->where('title','like', '%'.$keyword.'%');
        }      
        if(isset($request->status)){
            $status = $request->status;
            $orderObj = $orderObj->where('status',$status);
        }else{
            $orderObj = $orderObj->whereNotIn('status',array('pending','cancel','payment'));
        }
        if(isset($request->shop)){
            $shop = $request->shop;
            $orderObj = $orderObj->where('user_id',$shop);
        } 
        $shops = User::where('type','!=','admin')->get();
        $orders = $orderObj->paginate(20);
        return view('Admin.Orders.index',compact('orders','keyword','shop','status','shops'));
    }

    public function show(Request $request, Order $order){
        if($order->type == 'order_desk'){
            $adminProducts = array();
            $items = $order->orderDeskOrderItem()->with('attributes')->get();
            foreach($items as $item){
                $adminProducts[$item->supplier_code] = \App\AdminProduct::where('supplier_code',$item->admin_supplier_code)->first();
            }
            $partner = User::where('id',$order->user_id)->first();//Auth::user();
            return view('Admin.Orders.show1',compact('order','items','partner','adminProducts'));
        }
        $userProducts = array();
        foreach($order->orderItem as $item){
            $userProducts[$item->id] = UserProduct::where('id',$item->user_product_id)->with('adminProduct')->with('productPrintingGroupOption')->first();
        }
        $partner = User::where('id',$order->user_id)->first();
        return view('Admin.Orders.show',compact('order','userProducts','partner'));
    }


    public function fileview(Request $request)
    {
        return View('Admin.Orders.file');   
    }

    /*
    * input is .csv file
    *   columns that need to be imported are 
    *  code, sku, name, desc, attribute group(comma seprated),printing group(comma seprated),blank price, image url, category
    */

    public function fileupload(Request $request)
    {
        $rules = array(
            'orders' => 'required|mimes:csv,txt'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        
        $file = $request->file('orders');
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $content = $file->get();
        $import_array = explode("\n", $content);
        $header = explode(',',$import_array[0]);
        unset($import_array[0]);
        $tableHeader = array('email'=>'email','order date'=>'order_date','order id'=>'order_id','sku'=>'sku','quantity'=>'quantity','attributes'=>'attributes','first name'=>'first_name','last name'=>'last_name','address1'=>'address','address2'=>'address2','phone'=>'phone','city'=>'city','zip'=>'zip','province'=>'province','country'=>'country','company'=>'company','country code'=>'country_code','province code'=>'province_code');
        $finalAttr = array(); $orderIDIndex = $skuIndex = null;
        foreach($header as $index=>$head){
            if(!empty($tableHeader[strtolower(trim($head))])){
                $var = $tableHeader[strtolower(trim($head))];
                $$var = $index;
            }
        }
        if(empty($sku)){
            return Redirect::back()->withInput($request->all())->withErrors(array('sku'=>'sku not found'));
        }
        $invalidShipment = $shimmentChargesArr = $ordersArr = $skuArr = $invalidSkuArr = array();
        foreach($import_array as $index=>$item){
            if(empty($item)){
                break;
            }
            $itemDetails = preg_split('/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $item,-1,PREG_SPLIT_DELIM_CAPTURE);
            if(empty($itemDetails[$sku])){
                $invalidSkuArr[$index] = $index+1;
                continue;
            }
            $shipmenData = CountryCode::where('code2',$itemDetails[$country_code])->first();
            
            if(empty($shipmenData->id)){
                $invalidShipment[$index] = 'Invalid Country Code on CSV line# '.(($index*1)+1);
            }else{
                $shimmentChargesArr[$itemDetails[$country_code]] = $shipmenData;
            }

            $userProduct = UserProduct::where('sku',$itemDetails[$sku])->first();
            if(empty($userProduct->id)){
                $invalidSkuArr[$index] = 'Invalid sku on csv line# '.(($index*1)+1);
            }else{
                $skuArr[$itemDetails[$sku]] = $userProduct;
                $ordersArr[$itemDetails[$order_id]][] = $itemDetails;
            }
        }
        if(!empty($invalidSkuArr) || !empty($invalidShipment)){
            return Redirect::back()->withInput($request->all())->withErrors(array('lines'=>$invalidSkuArr,'shimpment'=>$invalidShipment));
        }
        $userId = Auth::user()->id;
        $orderNotImported = array();
        foreach($ordersArr as $orderID=>$orderData){
            $order = Order::where('shopify_order_id',$orderID)->where('user_id',Auth::user()->id)->first();
            if(!empty($order->id) && $order->status != 'pending'){
                $orderNotImported[] = 'Order# '.$orderID.' already exist and is not pending';
                continue;
            }else if(empty($order)){
                $order = new Order;    
            }
            $order->user_id = $userId;
            $order->name = $orderData[0][$first_name].' '.$orderData[0][$last_name];
            $order->email = $orderData[0][$email];
            $order->shopify_order_id = $orderID;
            $order->order_date = date('Y-m-d H:i:s',strtotime(trim($orderData[0][$order_date],'"')));
            $order->shipment = $shimmentChargesArr[$orderData[0][$country_code]]->shipment_charges;
            $order->country = $orderData[0][$country_code];
            $order->items = 0;
            $order->save();
            $orderItemsTotal = $quantity = 0;
            $orderShipmentObj = new OrderShipment;
            $order->orderItem()->delete();
            foreach($orderData as $index=>$itemData){
                if($index == 0){
                    $orderShipmentObj->first_name = $itemData[$first_name];
                    $orderShipmentObj->last_name = $itemData[$last_name];
                    $orderShipmentObj->name = $orderShipmentObj->first_name.' '.$orderShipmentObj->last_name;
                    $orderShipmentObj->address1 = trim($itemData[$address],'"');
                    $orderShipmentObj->address2 = trim($itemData[$address2],'"');
                    $orderShipmentObj->phone = $itemData[$phone];
                    $orderShipmentObj->city = $itemData[$city];
                    $orderShipmentObj->zip = $itemData[$zip];
                    $orderShipmentObj->province = $itemData[$province];
                    $orderShipmentObj->country = $itemData[$country];
                    $orderShipmentObj->company = $itemData[$company];
                    $orderShipmentObj->country_code = $itemData[$country_code];
                    $orderShipmentObj->province_code = $itemData[$province_code];
                    $order->orderShipment()->delete();
                    $order->orderShipment()->save($orderShipmentObj);
                }
                $orderItem = new OrderItem;
                $userProduct = $skuArr[$itemData[$sku]];
                $orderItem->title = $userProduct->name;
                $orderItem->name = $userProduct->name;
                $orderItem->sku = $itemData[$sku];
                $orderItem->quantity = $itemData[$quantity] *1;
                $quantity += $itemData[$quantity] *1;
                $orderItem->price = $orderItem->quantity * $userProduct->charge_amount;
                $orderItemsTotal += $orderItem->price;
                $attributesTrim = trim($itemData[$attributes],'"');
                $attributesData = explode(',',$attributesTrim);
                $attributeArr = array();
                foreach($attributesData as $at){
                    $atVaules = explode(':',$at);
                    $attributeArr[$atVaules[0]] = $atVaules[1];
                }
                $orderItem->attributes = json_encode($attributeArr);
                $orderItem->user_product_id = $userProduct->id;
                $order->orderItem()->save($orderItem);
            }
            $order->items = count($orderData);
            $order->quantity = $quantity;
            $order->additional_shipment = $quantity - 1 * $shimmentChargesArr[$orderData[0][$country_code]]->additional_charge;
            $order->charge = $orderItemsTotal;
            $order->order_total = $order->charge +  $order->additional_shipment+ $order->shipment;
            $order->save();
        }
        flash('Successfully uploaded the Content!','success');
        return redirect('orders/');
    }
}