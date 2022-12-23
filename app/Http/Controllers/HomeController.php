<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $shop = Auth::user();
        if(empty($shop->currency)){
            $shop1 = $shop->api()->rest('GET', '/admin/api/2020-10/shop.json');
            $shop->currency = $shop1['body']['shop']['currency'];
            $shop->save();
        }
        
        $lists = $shop->api()->rest('GET', '/admin/api/2020-10/fulfillment_services.json');
        if(!empty($lists['body']->container['fulfillment_services'][0]['name']) && $lists['body']->container['fulfillment_services'][0]['name'] != 'OrderDeskOrderSync'){
            $temp["name"] = "OrderDeskOrderSync";
            $temp["callback_url"] = "https://fundsup.com.au/public/fulfillment";
            $temp["inventory_management"] = true;
            $temp["tracking_support"] = true;
            $temp["requires_shipping_method"] = true;
            $temp["format"] = "json";
            $data["fulfillment_service"] = $temp;
            $fulfillmentData = $shop->api()->rest('POST', '/admin/api/2020-10/fulfillment_services.json',$data);
        }
        
        return view('home',compact('shop'));
    }
    
    public function webhooks(){
        ini_set('memory_limit','512M');
        $shop = Auth::user();
        echo $shop->name;
        $domain = $shop->getDomain()->toNative();
        $webhooks = $shop->api()->rest('GET', '/admin/api/2020-10/webhooks.json');
        $file = fopen('webhook.txt','w');
        fwrite($file,print_r($webhooks,true));
        fclose($file);
        die('done');
    }
    
    public function adminwelcome(Request $request)
    {
        return view('welcomeadmin');   
    }

    public function customerwelcome(Request $request){
        return view('welcomecustomer');   
    }

    public function orderdeskwelcome(Request $request){
        return view('orderdeskwelcome');   
    }
}
