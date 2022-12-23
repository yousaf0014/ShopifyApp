<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http;

use App\Order;
use App\OrderDeskOrderItem;
use App\OrderDeskItemPrintingAttribute;
use App\OrderShipment;
use App\AdminProduct;
use App\CountryCode;
use App\user;


class OrdersController extends BaseController 
{   
    function index(Request $request){
        //$user = Auth::user();
        //$userData['name'] = $user->name;
        //$userData['recipe_id'] = $user->recipe_id;
        //$userData['partner_billing_id'] = $user->partner_billing_id;
        //return $this->sendResponse($userData);
    }

    public function getOrder(Request $request){
        $data = $request->all();
        //die('done');

        $returnMessage = array();
        ini_set('memory_limit','512M');
        $order = Order::where('shopify_order_id',"{$data['id']}")->where('type','order_desk')->first();
        if(!empty($order->id)){
            $orderData = json_decode($order->data,true);
            $returnMessage['status'] = 'success';
            $returnMessage['data'] = $orderData;
            $returnMessage['message'] = 'Order cancel successfully';
            return $this->sendResponse($returnMessage,200);
        }

    }

    public function cancel(Request $request){
        $data = $request->all();
        //echo '<pre>';
        //print_r($data);
        //die('done');

        $returnMessage = array();
        ini_set('memory_limit','512M');
        $order = Order::where('shopify_order_id',"{$data['id']}")->where('type','order_desk')->first();
        if(!empty($order->id) && $order->status == 'pending' ){
            $order->status = 'cancel';
            $orderData = json_decode($order->data,true);
            $orderData['status'] = 'cancel';
            $order->data = json_encode($orderData);
            $order->save();
            $returnMessage['status'] = 'success';
            $returnMessage['message'] = 'Order cancel successfully';
            return $this->sendResponse($returnMessage,200);
        }
        $returnMessage['status'] = 'error';
        $returnMessage['message'] = 'Order Cannot be changed/Updated';
        return $this->sendResponse($returnMessage,200);
    }

    public function create(Request $request){
        $data = $request->all();
        //echo '<pre>';
        //print_r($data);
        //die('done');

        $returnMessage = array();
        ini_set('memory_limit','512M');
        $order = Order::where('shopify_order_id',"{$data['id']}")->where('type','order_desk')->first();
        if(!empty($order->id) && !($order->status == 'pending' || $order->status == 'cancel') ){
            $returnMessage['status'] = 'error';
            $returnMessage['message'] = 'Order Cannot be changed/Updated';
            return $this->sendResponse($returnMessage,200);
        }else if(!empty($order->id)){
            $order->orderDeskOrderItem()->delete();
            $order->orderShipment()->delete();
        }
        
        if(!isset($data['order_items']) || empty($data['order_items'])){
            $returnMessage['status'] = 'error';
            $returnMessage['message'] = 'No item found';
            return $this->sendResponse($returnMessage,200);
        }

        $invalidSide = $itemPrintingOptions = $invalidProductItems = $itemsArr = $orderItemsLocal = array();
        $amount = $quantites = 0;
        foreach($data['order_items'] as $index=>$item){
            if(!empty($item['code'])){
                $aProduct = AdminProduct::where('supplier_code',"{$item['code']}")->with('productPrintingGroup')->first();
                if(empty($aProduct)){
                    $invalidProductItems[$index] = $item['code'];
                    continue;
                }
                
                $aProductPrintingOptions = \App\PrintingGroupAttribute::where('printing_group_id',$aProduct->productPrintingGroup[0]->id)->get();
                $validPrintoptions = array();
                foreach($aProductPrintingOptions as $option){
                    $validPrintoptions[strtolower($option->name)] = $option->amount;
                }
                
                $orderItemsLocal[$item['id']] = $aProduct;
                $orderItem = new OrderDeskOrderItem;
                $orderItem->name = $item['name'];
                $orderItem->admin_supplier_code = $item['code'];
                $orderItem->admin_product_id = $aProduct->id;
                $orderItem->delivery_type = $item['delivery_type']; 
                $orderItem->quantity = $item['quantity'];
                $quantites+= $item['quantity'];
                $orderItem->base_price =  $aProduct->price;
                //$amount += ($aProduct->price * $item['quantity']);
                $orderItem->order_desk_id = "{$item['id']}";
                $orderItem->status = 'pending';
                $attribute = json_encode($item['variation_list']);
                $orderItem->varient_list = "$attribute";
                
                $printAmount = 0;
                for($i=0;$i<=10;$i++){
                    if(isset($item['metadata']['print_location_'.$i]) && isset($item['metadata']['print_mockup_'.$i]) && isset($item['metadata']['print_url_'.$i]) && !empty($validPrintoptions[strtolower($item['metadata']['print_location_'.$i])])){
                        $itemPrintingOptionObj = new OrderDeskItemPrintingAttribute;
                        $itemPrintingOptionObj->side = $item['metadata']['print_location_'.$i];
                        $itemPrintingOptionObj->mockup = $item['metadata']['print_mockup_'.$i];
                        $itemPrintingOptionObj->design = $item['metadata']['print_url_'.$i];
                        $itemPrintingOptionObj->price = $validPrintoptions[strtolower($item['metadata']['print_location_'.$i])];
                        $itemPrintingOptions[$item['id']][] = $itemPrintingOptionObj;
                        $printAmount += $itemPrintingOptionObj->price;
                        
                    }
                    if(isset($item['metadata']['print_location_'.$i]) && empty($validPrintoptions[strtolower($item['metadata']['print_location_'.$i])])){
                        $invalidSide[$item['code']][] =  $item['metadata']['print_location_'.$i];
                    }
                }
                $orderItem->total_price  = ($orderItem->base_price + $printAmount) * $orderItem->quantity;
                $amount += $orderItem->total_price;
                $itemsArr[$item['id']] = $orderItem;
            
            }
        }
        if(count($itemsArr) < 1){
            exit; 
        }
        $errorMessage = $seprator = '';
        if(!empty($invalidProductItems)){
            $invaliditems = implode(',',$invalidProductItems);
            $errorMessage .= 'Invalid item codes ' . $invaliditems;
            $seprator = ',\n\r  ';
        }
        if(!empty($invalidSide)){
            foreach($invalidSide as $item=>$sides){
                $sideslist = implode(',',$sides);
                $errorMessage .= ($seprator." For item code#{$item} Invalid printing sides " . $sideslist);                    
            }
        }
        
        if(!empty($errorMessage)){
            $returnMessage['status'] = 'error';
            $returnMessage['message'] = $errorMessage;
            return $this->sendResponse($returnMessage,200);
        }
        $shop = Auth::user(); //User::where('name',$this->shopDomain)->first();
        if(empty($order->id)){
            $order = new Order;
        }
        $order->items = count($itemsArr);
        $order->user_id = $shop->id;
        $data['status'] = 'pending';
        $order->data = json_encode($data);
        $order->name = $data['shipping']['first_name'].' '.$data['shipping']['last_name'];
        $order->email = $shop->email; //$data['email'];
        $order->shopify_order_id = "{$data['id']}";
        $order->order_date = date('Y-m-d H:i:s',strtotime($data['date_added']));
        $order->country = $data['shipping']['country'];
        $order->status = 'pending';
        $order->quantity = $quantites;
        $order->order_total = $amount; // + $order->shipment + $order->additional_shipment;
        $order->type = 'order_desk';
        $order->save();
        foreach($itemsArr as $itemId=>$item){
            $itemIDObj = $order->orderDeskOrderItem()->save($item);
            foreach($itemPrintingOptions[$itemId]  as $option){
                $itemIDObj->attributes()->save($option);
            }
        }
        $orderShipment = new OrderShipment;
        $orderShipment->first_name = $data['shipping']['first_name'];
        $orderShipment->last_name = $data['shipping']['last_name'];
        $orderShipment->name = $data['shipping']['first_name'].' '.$data['shipping']['last_name'];
        $orderShipment->address1 = $data['shipping']['address1'];
        $orderShipment->address2 = $data['shipping']['address2'];
        $orderShipment->phone = $data['shipping']['phone'];
        $orderShipment->city = $data['shipping']['city'];
        $orderShipment->zip = $data['shipping']['postal_code'];
        $orderShipment->province = $data['shipping']['state'];
        $orderShipment->country = $data['shipping']['country'];
        $orderShipment->latitude = '';
        $orderShipment->longitude = '';
        $orderShipment->country_code = $data['shipping']['country'];
        $orderShipment->province_code = $data['shipping']['state'];
        $order->orderShipment()->save($orderShipment);
        $shipmentChargesData = CountryCode::where('code2',$data['shipping']['country'])->first();
        
        $order->additional_shipment = $shipmentChargesData->additional_charge*($quantites- 1);
        $order->shipment = $shipmentChargesData->shipment_charges;
        $order->charge = $amount;
        $order->order_total = $order->additional_shipment + $order->shipment + $order->charge;
        $order->save();

        $shop->sendOrderCreationNotification($order);
        $newNotification = new \App\Notification;
        $newNotification->title = 'New order Created From '.$order->name;
        $url = url('storeOrders/'.$order->id);
        $url = str_replace('http://', 'https://', $url);
                    
        $newNotification->details = 'New order received from '.$order->name.'. To view Details click <a href="'.$url.'">View</a>';
        $shop->notification()->save($newNotification);
        die('done');
    
    }
}