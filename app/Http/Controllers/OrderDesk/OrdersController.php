<?php
namespace App\Http\Controllers\OrderDesk;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;

use App\Order;
use App\OrderDeskOrderItem;
use App\OrderDeskItemPrintingAttribute;
use App\OrderShipment;
use App\user;
use App\UserProdcutVariant;
use App\UserProduct;
use App\CountryCode;
use App\StoreProduct;

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
    
    public function index(Request $request)
    {
        $auth = Auth::user();
        $orderObj =  new Order;
        $keyword = $status = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $orderObj = $orderObj->where('title','like', '%'.$keyword.'%');
        }      
        if(isset($request->status)){
            $status = $request->status;
            $orderObj = $orderObj->where('status',$status);
        }
        
        $orderObj = $orderObj->where('user_id',$auth->id);
        $orders = $orderObj->orderBy('id','Desc')->paginate(20);
        return view('OrderDesk.Orders.index',compact('orders','keyword','status'));
    }

    public function show(Request $request, Order $order){
        $adminProducts = array();
        $items = $order->orderDeskOrderItem()->with('attributes')->get();
        foreach($items as $item){
            $adminProducts[$item->supplier_code] = \App\AdminProduct::where('supplier_code',$item->admin_supplier_code)->first();
        }
        $partner = Auth::user();
        return view('OrderDesk.Orders.show',compact('order','items','partner','adminProducts'));
    }

    
    public function fileview(Request $request)
    {
        $stores = $this->stores;$selectedStore = '';
        return View('OrderDesk.Orders.file',compact('stores','selectedStore'));   
    }

    /*
    * input is .csv file
    *   columns that need to be imported are 
    *  code, sku, name, desc, attribute group(comma seprated),printing group(comma seprated),blank price, image url, category
    */

    public function fileupload(Request $request)
    {
        $rules = array(
            'store' => 'required|int',
            'orders' => 'required|mimes:csv,txt'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        
        $userId = $request->store;
        $file = $request->file('orders');
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $content = $file->get();
        $import_array = explode("\n", $content);
        $header = explode(',',$import_array[0]);

        $tableHeader = array('email'=>'email','order date'=>'order_date','order id'=>'order_id','sku'=>'sku','quantity'=>'quantity','attributes'=>'attributes','first name'=>'first_name','last name'=>'last_name','address1'=>'address','address2'=>'address2','phone'=>'phone','city'=>'city','zip'=>'zip','province'=>'province','country'=>'country','company'=>'company','country code'=>'country_code','province code'=>'province_code');
        $finalAttr = array(); $orderIDIndex = $skuIndex = null;
        foreach($header as $index=>$head){
            if(!empty($tableHeader[strtolower(trim($head))])){
                $$tableHeader[strtolower(trim($head))] = $index;
            }
        }

        unset($import_array[0]);
        if(empty($skuIndex)){
            return Redirect::back()->withInput($request->all())->withErrors(array('sku'=>'sku not found'));
        }
        $invalidShipment = $shimmentChargesArr = $ordersArr = $skuArr = $invalidSkuArr = array();
        foreach($import_array as $index=>$item){
            $itemDetails = preg_split('/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $line,-1,PREG_SPLIT_DELIM_CAPTURE);
            if(empty($itemDetails[$skuIndex])){
                $invalidSkuArr[$index] = $index+1;
                continue;
            }
            $userProduct = UserProduct::where('sku',$itemDetails[$sku])->first();
            $shipmenData = CountryCode::where('code2',$country_code)->first();
            if(empty($shipmenData->id)){
                $invalidShipment[$index] = 'Invalid Country Code on line:'.($index *1 +1);
            }else{
                $shimmentChargesArr[$item[$country_code]] = $shipmenData;
            }
            if(empty($userProduct->id)){
                $invalidSkuArr[$index] = 'Invalid sku on line:'.($index*1+1);
            }else{
                $skuArr[$item[$sku]] = $userProduct;
                $ordersArr[$item[$order_id]][] = $itemDetails;
            }
        }
        if(!empty($invalidSkuArr) || !empty($invalidShipment)){
            return Redirect::back()->withInput($request->all())->withErrors(array('sku'=>'sku not found','lines'=>'invalidSkuArr','shimpment'=>$invalidShipment));
        }
        //$userId = Auth::user()->id;
        foreach($ordersArr as $orderID=>$orderData){
            $order = new Order;
            $order->user_id = $userId;
            $order->name = $orderData[0][$first_name].' '.$orderData[0][$last_name];
            $order->email = $orderData[0][$email];
            $order->shopify_order_id = $oderID;
            $order->order_date = date('Y-m-d H:i:s',strtotime($orderData[0][$order_date]));
            $order->shipment = $shimmentChargesArr[$orderData[0][$country_code]]->shipment;
            $order->items = 0;
            $order->save();
            $orderItemsTotal = $quantity = 0;
            $orderShipmentObj = new OrderShipment;
            foreach($orderData as $index=>$itemData){
                if($index == 0){
                    $orderShipmentObj->first_name = $itemData[$first_name];
                    $orderShipmentObj->last_name = $itemData[$last_name];
                    $orderShipmentObj->name = $orderShipmentObj->first_name.' '.$orderShipmentObj->last_name;
                    $orderShipmentObj->address1 = $itemData[$address];
                    $orderShipmentObj->address2 = $itemData[$address2];
                    $orderShipmentObj->phone = $itemData[$phone];
                    $orderShipmentObj->city = $itemData[$city];
                    $orderShipmentObj->zip = $itemData[$zip];
                    $orderShipmentObj->province = $itemData[$province];
                    $orderShipmentObj->country = $itemData[$country];
                    $orderShipmentObj->company = $itemData[$company];
                    $orderShipmentObj->country_code = $itemData[$country_code];
                    $orderShipmentObj->province_code = $itemData[$province_code];
                    $order->orderShipment()->save($orderShipmentObj);
                }
                $orderItem = new OrderItem;
                $userProduct = $skuArr[$itemData[$sku]];
                $orderItem->title = $userProduct->name;
                $orderItem->name = $userProduct->name;
                $orderItem->title = $userProduct->title;
                $orderItem->quantity = $itemData[$quantity] *1;
                $quantity += $itemData[$quantity] *1;
                $orderItem->price = $orderItem->quantity * $userProduct->charge_amount;
                $orderItemsTotal += $orderItem->price;
                $orderItem->attributes = $itemData[$attributes];
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
        return redirect('storeOrders/');
    }

    public function approve(Request $request,Order $order){
        $user = Auth::user();
        if($order->user_id == $user->id ){
           $order->status = 'payment';
           $order->save();
            $data = json_decode($order->data);
            $data['status'] = 'payment_approved';
            $order->data = json_encode($data);
            $order->save();
        
           return 'success'; 
        }
        return 'error';
    }

    public function approveAll(Request $request){
        $userID = Auth::user()->id;
        $orderIds = $request->ids;
        foreach($orderIds as $id){
            $order = Order::where('id',$id)->first();
            if($order->user_id ==  $userID){
                $order->status = 'payment';
                $data = json_decode($order->data);
                $data['status'] = 'payment_approved';
                $order->data = json_encode($data);
                $order->save();
            }
        }
        return 'success';
    }
    public function cancel(Request $request,Order $order){
        $userID = Auth::user()->id;
        if($order->user_id == $userID){
            $order->status = 'cancel';
            $order->save();
            $data = json_decode($order->data);
            $data['status'] = 'order_canceled';
            $order->data = json_encode($data);
            $order->save();
           return 'success'; 
        }
        return 'error';


        //// need to update on order desk that Order Desk Admin have canceled its order
    }
    public function cancelAll(Request $request){
        $userID = Auth::user()->id;
        $orderIds = $request->ids;
        foreach($orderIds as $id){
            $order = Order::where('id',$id)->first();
            if($order->user_id == $userID){
                $order->status = 'cancel';
                $order->save();
                $data = json_decode($order->data);
                $data['status'] = 'order_canceled';
                $order->data = json_encode($data);
                $order->save();

                //// need to update on order desk that Order Desk Admin have canceled its order
            }
        }
        return 'success';
    }
}