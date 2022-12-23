<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\OrderDeskApiClient;
use App\Order;
class OrderDeskSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string  ///OrderDeskOrders
     */
    protected $signature = 'OrderDeskOrders:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Order Desk Orders Upload");
        $orderDeskApiClientObj = new OrderDeskApiClient(env('ORDER_DESK_STORE_ID'), env('ORDER_DESK_STORE_API_KEY'));
        $orders = Order::where('status','approved')->get();
        $imageBaseUrl = url('/').'/../storage/app/public/uploads/';
        foreach($orders as $order){
                if($order->type=='order_desk'){
                    $data = json_decode($order->data);
                    $data['id']  = $order->id;
                    $data['order_metadata'] = array(
                        "ship_notify_url" =>url('/').'/api/shipNotificationDesk/'.$order->id
                    );
                }else{
                    $orderItems = array();
                    foreach($order->orderItem as $item){
                        $userProductObj = \App\UserProduct::where('id',$item->user_product_id)->first();
                        $temp["name"] = $item->name;
                        $temp["price"] = $item->price;
                        $temp["quantity"] = $item->quantity;
                        $temp["code"] = $userProductObj->adminProduct->supplier_code;
                        $varientData = json_decode($item->attributes);
                        $temp["variation_list"] = array();
                        $code = $color = ''; 
                        foreach($varientData as $key=>$val){
                            $temp["variation_list"][$key] = $val;
                            if(strtolower($key) == 'color'){
                                $color = $val;
                                $code = getColorHash($userProductObj->admin_product_id,$color); 
                            }
                        }
                        
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
            }
            if($order->id == 20){
                $file = fopen('order_desk_order.txt','w');
                fwrite($file,print_r($data,true));
                fclose($file);
            }

            $response = $orderDeskApiClientObj->post('orders',$data);
            $order->status = 'in_process';
            if($order->type=='order_desk'){
                $orderData = json_decode($order->data);
                $orderData->status = 'in_process';
                $order->data = json_encode($orderData);
            }
            $order->save();
        }
        die('done');
        
    }
}
