<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use Auth;
use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;

class StoresController extends Controller
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
        $userObj = new User;
        $userObj = $userObj->where('parent_id',Auth::user()->id);
        $stores = $userObj->paginate();
        return view('Customer.Stores.index',compact('stores'));
    }

    public function addShopifyStore()
    {
        return view('Customer.Stores.addShopify');
    }

    public function storeData(Request $request)
    {
        // Create options for the API
        $options = new Options();
        $options->setVersion('2020-01');
        $options->setApiKey(env('SHOPIFY_API_KEY'));
        $options->setApiSecret(env('SHOPIFY_API_SECRET'));

        // Create the client and session
        $api = new BasicShopifyAPI($options);
        $api->setSession(new Session($request->shop));

        if (!$request->code) {
            $code = $request->code;
            /**
            * No code, send user to authorize screen
            * Pass your scopes as an array for the first argument
            * Pass your redirect URI as the second argument
            */
            $redirect = $api->getAuthUrl(env('SHOPIFY_API_SCOPES'), env('SHOPIFY_API_REDIRECT_URI'));
            header("Location: {$redirect}");
            exit;
        } else {
            // We now have a code, lets grab the access token
            $accessToken = $api->requestAndSetAccess($request->code);
            // You can now make API calls
            $shopData = $api->rest('GET', '/admin/shop.json'); // or GraphQL
            $user = User::where('name',$shopData['body']->container['shop']['domain'])->withTrashed()->first();
            if(empty($user->id)){
                $user = new User;
            }
            if(empty($user->shopify_location_id)){
                $locations = $api->rest('GET', '/admin/locations.json');
                foreach($locations['body']->locations as $loc){
                    if($loc['name'] == env('ShopifyAppName')){
                        $user->shopify_location_id = "{$loc['id']}";
                        break;
                    }
                }
            }

            $user->password = $accessToken['access_token'];
            $user->name = $shopData['body']->container['shop']['domain'];
            $user->email = 'shop@'.$shopData['body']->container['shop']['domain'];
            $user->currency = $shopData['body']->container['shop']['enabled_presentment_currencies'][0];
            $user->parent_id = Auth::user()->id;
            $user->deleted_at = null;
            $user->type = 'shop';
            $user->save();
            $createOrder['webhook']['topic'] = env('SHOPIFY_WEBHOOK_1_TOPIC');
            $createOrder['webhook']['address'] = env('SHOPIFY_WEBHOOK_1_ADDRESS');
            $createOrder['webhook']['format'] = 'json';
            $createorderData = $api->rest('POST', '/admin/webhooks.json',$createOrder);
            $uninstall['webhook']['topic'] = env('SHOPIFY_WEBHOOK_UNINSTALL');
            $uninstall['webhook']['address'] = env('SHOPIFY_WEBHOOK_UNINSTALL_ADDRESS');
            $uninstall['webhook']['format'] = 'json';
            $uninstallData = $api->rest('POST', '/admin/webhooks.json',$uninstall);


            $temp["name"] = "OrderDeskOrderSync";
            $temp["callback_url"] = "https://fundsup.com.au/public/fulfillment";
            $temp["inventory_management"] = true;
            $temp["tracking_support"] = true;
            $temp["requires_shipping_method"] = true;
            $temp["format"] = "json";
            $data["fulfillment_service"] = $temp;
            $fulfillmentData = $api->rest('POST', '/admin/fulfillment_services.json',$data);
            return redirect('stores');
        }
        return view('Customer.Stores.addShopify');
    }


    public function create(){
        return View('Customer.Stores.create');
    }

    public function store(Request $request){
        $rules = array(
            'name' => 'required|string',
            'email' => 'nullable|email',
            'currency' =>'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $auth = Auth::user();
            $data = $request->all();
            $userObj = new User;
            $userObj->name = $request->name;
            $userObj->email = empty($request->email) ? $auth->email:$request->email;
            $userObj->password = bcrypt('L@h0%$14%^234"!8&KiR');
            $userObj->currency = $request->currency;
            $userObj->parent_id = $auth->id;
            $userObj->type = 'store';
            $userObj->save();
            flash('Successfully Saved.','success');
            return redirect('stores');
        }
    }

    public function show(User $user)
    {
        return View('Customer.Stores.show',compact('user'));   
    }

    public function edit(User $user){
        return View('Customer.Stores.edit',compact('user'));
    }

    public function update(Request $request,User $user){
        $rules = array(
            'name' => 'required|string',
            'email' => 'nullable|email',
            'currency' =>'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $userObj->name = $request->name;
            $userObj->email = empty($request->email) ? $auth->email:$request->email;
            $userObj->password = bcrypt('L@h0%$14%^234"!8&KiR');
            $userObj->currency = $request->currency;
            $userObj->parent_id = $auth->id;
            $userObj->type = 'store';
            $userObj->save();
            flash('Successfully updated Content!','success');
            return redirect('stores');
        }
    }

    public function delete(User $user){
        $user->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('stores');
    }

    public function login(User $user){
        if($user->parent_id == Auth::user()->id){
            //Auth::login($user, $remember = true);
        }
        return Redirect::back()->withInput($request->all())->withErrors(array('error'=>'Store Not belongs to you. Please try again later'));
    }

}