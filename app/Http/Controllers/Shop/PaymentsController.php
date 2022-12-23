<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use Cartalyst\Stripe\Stripe;
use App\User;
use Auth;
use App\Order;
use App\UserPaymentMethod;
use App\OrderPayment;

class PaymentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //private $stores;
    public function __construct()
    {
        /*$this->middleware(function ($request, $next) {
            $authUser = Auth::user()->id;
            $this->stores = User::where('parent_id',$authUser)->get();
            return $next($request);
        });*/
        
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $payment = $user->paymentMethod()->first();
        $alreadyExist = !empty($payment->id) ? 'Already added! want to change then please click on add Card.':'Please add payment Information';
        $payments = \App\OrderPayment::where('user_id',$user->id)->orderBy('id','Desc')->paginate(20);
        
        return view('Shop.Payments.index',compact('alreadyExist','payments'));
    }

    public function addCard(){
        
        $user = Auth::user();
        $stripe = new Stripe(env('STRIPE_SECRECT'));
        if(empty($user->stripe_id)){
            $customer = $stripe->customers()->create([
                'email' => $user->email,
                'name'  => $user->name
            ]);
            $user->stripe_id = $customer['id'];
            $user->save();
        }
        $intentData = $stripe->setupIntents()->create(['customer' => $user->stripe_id]);
        return view('Shop.Payments.addcard',compact('intentData'));

    }

    public function savePaymentMethod(Request $request,$method){
        $user = Auth::user();
        $paymentMethodObj = new UserPaymentMethod;
        $paymentMethodObj->method_id = $method;
        $user->paymentMethod()->delete();
        $user->paymentMethod()->save($paymentMethodObj);
        return Redirect('/paymentInfoShop');
    }

    public function doPayment(Request $request,Order $order){
        $order->status = 'payment';
        $order->save();
        $user = Auth::user();
        $intent = $user->paymentMethod()->first();
        $paymentMethod = $stripe->paymentMethods()->create([
            'type' => 'card'
        ]);

        $stripe = new Stripe(env('STRIPE_SECRECT'));
        $payment = $stripe->paymentIntents()->create([
            'amount' => $order->order_total,
            'currency' => 'aud',
            'customer'  => $user->stripe_id,
            'payment_method'=> $intent->method_id,
            'off_session'=>true,
            'confirm'=>true,
        ]);
        if($payment['status'] === 'succeeded'){
            $order->status = 'approved';
            $order->save();

            $orderPayment = new OrderPayment;
            $orderPayment->amount = $order->order_total;
            $orderPayment->user_id = $order->user_id;
            $orderPayment->payment_id = $payment['id'];
            $orderPayment->stripe_date = json_encode($payment);
            $order->orderPayment()->save();

            $newNotification = new \App\Notification;
            $newNotification->title = 'Payment for the order #'.$oder->shopify_order_id.' is successfull';
            $newNotification->details = 'Payment for the <a href="'.url('storeOrders/'.$oder->id).'"> Order #<'.$oder->shopify_order_id.'</a> amount:'.$order->order_total.' AUD is successfull';

            $user->notification()->save($newNotification);
        }else{
            $newNotification = new \App\Notification;
            $newNotification->title = 'Error in Payment';
            $newNotification->details = 'Error in payment, Please check your details(Credentials,balance,etc) And add card. Thanks!';
            $user->notification()->save($newNotification);
        }
        exit;
    }    
}