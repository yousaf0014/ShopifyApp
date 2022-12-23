<?php 
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Contracts\Objects\Values\ShopDomain;
use stdClass;
use Log;
use App\Order;
use App\OrderItem;
use App\OrderShipment;
use App\user;
use App\UserProdcutVariant;
use App\UserProduct;
use App\CountryCode;

class OrdersCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param ShopDomain $shopDomain The shop's myshopify domain
     * @param stdClass   $data       The webhook data (JSON decoded)
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("Order received!");
        ini_set('memory_limit','512M');
        $orderData = Order::where('shopify_order_id',"{$this->data->id}")->first();
        if(!empty($orderData)){
            return;
        }
        
        $orderItemsLocal = array();
        $amount = $quantites = 0;
        foreach($this->data->line_items as $item){
            if(!empty($item->variant_id)){
                $cProduct = UserProdcutVariant::where('shopify_variant_id',"{$item->variant_id}")->with('userProduct')->first();
                if(!empty($cProduct->id)){
                    $orderItemsLocal[$item->variant_id] = $cProduct;
                    $orderItem = new OrderItem;
                    $orderItem->title = $item->title;
                    $orderItem->sku = $item->sku;
                    $orderItem->name = $item->name;
                    $orderItem->quantity = $item->quantity;
                    $quantites+= $item->quantity;
                    $orderItem->price = $item->quantity * $cProduct->userProduct->charge_amount;
                    $amount += $orderItem->price;
                    $orderItem->shopify_product_id = "{$item->variant_id}";
                    $orderItem->shopify_parent_id = "{$item->product_id}";
                    $orderItem->order_itme_id = "{$item->id}";
                    $orderItem->user_product_id = $cProduct->userProduct->id;
                    $orderItem->status = 'pending';
                    $orderItem->attributes = $cProduct->options;
                    $orderItemsLocal[$item->variant_id] = $orderItem;
                }
            }
        }
        if(count($orderItemsLocal) < 1){
            exit; 
        }
        
        $shop = User::where('name',$this->shopDomain)->first();
        $order = new Order;
        $order->items = count($orderItemsLocal);
        
        $order->user_id = $shop->id;
        $order->data = json_encode($this->data);
        $order->name = $this->data->billing_address->name;
        $order->email = $this->data->email;
        $order->shopify_order_id = "{$this->data->id}";
        $order->order_date = date('Y-m-d H:i:s',strtotime($this->data->created_at));
        $order->country = $this->data->shipping_address->country_code;
        $order->status = 'pending';
        $order->quantity = $quantites;
        $order->order_total = $amount + $order->shipment + $order->additional_shipment;
        $order->save();
        foreach($orderItemsLocal as $itemId=>$item){
            $order->orderItem()->save($item);
        }

        $orderShipment = new OrderShipment;
        $orderShipment->first_name = $this->data->shipping_address->first_name;
        $orderShipment->last_name = $this->data->shipping_address->last_name;
        $orderShipment->name = $this->data->shipping_address->name;
        $orderShipment->address1 = $this->data->shipping_address->address1;
        $orderShipment->address2 = $this->data->shipping_address->address2;
        $orderShipment->phone = $this->data->shipping_address->phone;
        $orderShipment->city = $this->data->shipping_address->city;
        $orderShipment->zip = $this->data->shipping_address->zip;
        $orderShipment->province = $this->data->shipping_address->province;
        $orderShipment->country = $this->data->shipping_address->country;
        $orderShipment->latitude = $this->data->shipping_address->latitude;
        $orderShipment->longitude = $this->data->shipping_address->longitude;
        $orderShipment->country_code = $this->data->shipping_address->country_code;
        $orderShipment->province_code = $this->data->shipping_address->province_code;
        $order->orderShipment()->save($orderShipment);
        $shipmentChargesData = CountryCode::where('code2',$this->data->shipping_address->country_code)->first();
        
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
        \Log::info("Order received Finish");
        die('done');
    }
}