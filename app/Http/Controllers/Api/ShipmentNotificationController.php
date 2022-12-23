<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use App\Order;
use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;


class ShipmentNotificationController extends Controller 
{   
    function index(Request $request,Order $order){
        $data = $request->all();
        $file = fopen('shipment.txt','w');
        fwrite($file, json_encode($order));
        fwrite($file, print_r($data,true));
        fclose($file);
        $shipment = $data['shipment'];
        $shop = User::where('id',$order->user_id)->first();
        $options1 = new Options();
        $options1->setVersion('2020-01');
        $options1->setApiKey(env('SHOPIFY_API_KEY'));
        $options1->setApiSecret(env('SHOPIFY_API_SECRET'));
        $api = new BasicShopifyAPI($options1);
        $api->setSession(new Session($shop->name, $shop->password, null));
        if(empty($shop->shopify_location_id)){
            $locations = $api->rest('GET', '/admin/locations.json');
            fwrite($file, json_encode($locations));
            foreach($locations['body']->locations as $loc){
                if($loc['name'] == env('ShopifyAppName')){
                    fwrite($file, json_encode($loc));
                    echo $shop->shopify_location_id = "{$loc['id']}";
                    $shop->save();
                    break;
                }
            }
        }
        $data1['fulfillment']['location_id'] = $shop->shopify_location_id;
        $data1['fulfillment']['tracking_number'] = $shipment['tracking_number']; //"123456789";
        $data1['fulfillment']['tracking_urls'] = array($shipment['tracking_url']);
        $data1['fulfillment']['notify_customer'] = true;
        $shipment = $api->rest('POST',"/admin/orders/".$order->shopify_order_id.'/fulfillments.json',$data1);
        $order->status = 'shipped';
        $orderData = json_decode($order->data,true);
        $orderData['status'] = 'shipped';
        $order->data = json_encode($orderData);
        $order->save();
        die('done');
    }

    function shipNotificationDesk(Request $request,Order $order){
        $data = $request->all();
        $file = fopen('shipment.txt','w');
        fwrite($file, json_encode($order));
        fwrite($file, print_r($data,true));
        fclose($file);
        $shipment = $data['shipment'];
        $orderData = json_encode($order->data,true);
        $orderData['shipment'] = $data['shipment'];
        $orderData['status'] = 'shipped';
        $order->data = json_encode($orderData);
        $shop = User::where('id',$order->user_id)->first();
        $options1 = new Options();
        $options1->setVersion('2020-01');
        $options1->setApiKey(env('SHOPIFY_API_KEY'));
        $options1->setApiSecret(env('SHOPIFY_API_SECRET'));
        $api = new BasicShopifyAPI($options1);
        $api->setSession(new Session($shop->name, $shop->password, null));
        if(empty($shop->shopify_location_id)){
            $locations = $api->rest('GET', '/admin/locations.json');
            fwrite($file, json_encode($locations));
            foreach($locations['body']->locations as $loc){
                if($loc['name'] == env('ShopifyAppName')){
                    fwrite($file,json_encode($loc));
                    echo $shop->shopify_location_id = "{$loc['id']}";
                    $shop->save();
                    break;
                }
            }
        }

        
        /*******************   here come the logic to send data to orderdesk for shipment  **************************/
        $cURLConnection = curl_init($orderData['order_metadata']['ship_notify_url']);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $orderData);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $jsonArrayResponse - json_decode($apiResponse);
        
        /*******************   End logic to send data to orderdesk for shipment  **************************/
        $order->status = 'shipped';
        $order->save();
        die('done');   
    }
}