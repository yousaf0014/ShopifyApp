<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\OrderDeskApiClient;
use App\Order;

class OrderSyncController extends Controller
{
    function index(){
        $orderDeskApiClientObj = new OrderDeskApiClient(env('ORDER_DESK_STORE_ID'), env('ORDER_DESK_STORE_API_KEY'));
        $data = $orderDeskApiClientObj->get('test');
        echo '<pre>';
        print_r($data);
        exit;
    }   

    function createOrder(){
        //$orderDeskApiClientObj = new OrderDeskApiClient(env('ORDER_DESK_STORE_ID'), env('ORDER_DESK_STORE_API_KEY'));
        $orders = Order::where('status','approved')->get();
        $imageBaseUrl = url('/').'/../storage/app/public/uploads/';
        foreach($orders as $order){
                $orderItems = array();
                foreach($order->orderItem as $item){
                    $userProductObj = \App\UserProduct::where('id',$item->user_product_id)->first();
                    $temp["name"] = $item->name;
                    $temp["price"] = $item->price;
                    $temp["quantity"] = $item->quantity;
                    $temp["code"] = $userProductObj->adminProduct->supplier_code;
                    $varientData = json_decode($item->attributes);
                    $code = $color = '';
                    foreach($varientData as $key=>$val){
                        $temp["variation_list"][$key] = $val;
                        if(strtolower($key) == 'color'){
                            $color = $val;
                            $code = getColorHash($userProductObj->admin_product_id,$color); 
                        }
                    }
                    //print_r($temp["variation_list"]);
                    $tempMetadata = array();
                    foreach($userProductObj->productPrintingGroupOption as $index=>$itemImages){
                        $artwork = getArtWork($item->user_product_id,$color);
                        $img = $itemImages->artwork;
                        if(!empty($artwork) && $artwork == 'dark'){
                            $img = $itemImages->artwork_dark;
                        }
                        $tempMetadata['print_location_'.$index] = $itemImages->name;
                        $tempMetadata['print_mockup_'.$index] = $imageBaseUrl.$itemImages->shirt_design;
                        $tempMetadata['print_url_'.$index] = $imageBaseUrl.$img;
                    }

                    $temp["variation_list"] = array_merge($tempMetadata,$temp["variation_list"]);
                    $temp["variation_list"]['color'] = $color;
                    $temp["variation_list"]['color_code'] = $code;
                    $temp["metadata"] = $tempMetadata;
                    $temp["metadata"]['color'] = $color;
                    $temp["metadata"]['color_code'] = $code;
                    $orderItems[] = $temp;
                }
                $data = array(
                "source_id" => $order->id,
                "email" => $order->email,
                "shipping_method" => "Australian post",  ///?? need to discuss
                "shipping_total" => $order->shipment+$order->additional_shipment,  //9.50, 
                "handling_total" => 0,
                "tax_total" => 1.25,    /// ?? need to discuss
                "date_added" => $order->order_date,
                "date_updated" => date('Y-m-d H:i:s'),
                "shipping" => array(
                    "first_name" => $order->orderShipment->first_name,
                    "last_name" => $order->orderShipment->last_name,
                    "company" => $order->orderShipment->company,
                    "address1" => $order->orderShipment->address1,
                    "address2" => $order->orderShipment->address2,
                    "city" => $order->orderShipment->city,
                    "state" => $order->orderShipment->province,
                    "postal_code" => $order->orderShipment->zip,
                    "country" => $order->orderShipment->country,
                    "phone" => $order->orderShipment->phone
                ),
                "customer" => array(
                    "first_name" => $order->orderShipment->first_name,
                    "last_name" => $order->orderShipment->last_name,
                    "company" => $order->orderShipment->company,
                    "address1" => $order->orderShipment->address1,
                    "address2" => $order->orderShipment->address2,
                    "city" => $order->orderShipment->city,
                    "state" => $order->orderShipment->province,
                    "postal_code" => $order->orderShipment->zip,
                    "country" => $order->orderShipment->country,
                    "phone" => $order->orderShipment->phone
                ),
                "return_address" => array(
                    "title" => "Acme",
                    "name" => "Doug Jones",
                    "company" => "Acme Manufacturing",
                    "address1" => "817 E Maple Ln",
                    "address2" => "",
                    "city" => "Knoxville",
                    "state" => "TN",
                    "postal_code" => "55555",
                    "country" => "US",
                    "phone" => "555-555-5555"
                ),
                "order_metadata" => array(
                    "ship_notify_url" =>url('/').'/api/shipNotification/'.$order->id
                ),
                "order_items" => $orderItems
            );
            echo '<pre>';
            print_r($data);
            //$response = $orderDeskApiClientObj->post('orders',$data);
            echo '<pre>';
            //print_r($response);
            $order->status = 'in_process';
            //$order->save();

        }
        die('done');
    }
}