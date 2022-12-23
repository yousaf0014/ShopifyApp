<?php
namespace App\Http\Controllers\Customer;

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
    private $stores;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authUser = Auth::user()->id;
            $this->stores = User::where('parent_id',$authUser)->get();
            return $next($request);
        });        
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $payment = $user->paymentMethod()->first();
        $alreadyExist = !empty($payment->id) ? 'Already added! want to change then please click on add Card.':'Please add payment Information';

        $userNames[$user->id] = $user->name;
        $usersArr[] = $user->id;
        foreach($this->stores as $st){
            $usersArr[] = $st->id;
            $userNames[$st->id] = $st->name; 
        }
        $payments = \App\OrderPayment::whereIn('user_id',$usersArr)->orderBy('id','Desc')->paginate(20);
        return view('Customer.Payments.index',compact('alreadyExist','payments','userNames'));
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
        return view('Customer.Payments.addcard',compact('intentData'));

    }

    public function addCard1(){
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
        /*$paymentMethod = $stripe->paymentMethods()->create([
            'type' => 'card',
            'card' => [
                'number' => '4000 0025 0000 3155',
                'exp_month' => 9,
                'exp_year' => 2022,
                'cvc' => '314'
            ],
        ]);*/
        return view('Customer.Payments.addcard',compact('intentData'));

    }

    public function savePaymentMethod(Request $request,$method){
        $user = Auth::user();
        $paymentMethodObj = new UserPaymentMethod;
        $paymentMethodObj->method_id = $method;
        $user->paymentMethod()->delete();
        $user->paymentMethod()->save($paymentMethodObj);
        return Redirect('/paymentInfo');
    }

    public function doPayment(Request $request){
        $stripe = new Stripe(env('STRIPE_SECRECT'));
        $pauymentMethods = \App\UserPaymentMethod::get();
        echo '<pre>';
        print_r($pauymentMethods);
        foreach($pauymentMethods as $intent){
            $userID = $intent->user_id;
            $user = \App\User::where('id',$userID)->first();
            $shops = \App\User::where('parent_id',$userID)->get();
            $usersArr[] = $userID;
            foreach($shops as $sh){
                $usersArr[] = $sh->id;
            }
            print_r($usersArr);
            $orders = \App\Order::whereIn('user_id',$usersArr)->where('status','payment')->get();
            print_r($orders);
            foreach($orders as $order){
                echo '<br>=============='.$order->order_total.'====================<br>';
                $payment = $stripe->paymentIntents()->create([
                    'amount' => $order->order_total,
                    'currency' => 'aud',
                    'customer'  => $user->stripe_id,
                    'payment_method'=> $intent->method_id,
                    'off_session'=>true,
                    'confirm'=>true,
                ]);
                print_r($payment);
                if($payment['status'] === 'succeeded'){
                    $order->status = 'approved';
                    $order->save();

                    $orderPayment = new \App\OrderPayment;
                    $orderPayment->amount = $order->order_total;
                    $orderPayment->user_id = $order->user_id;
                    $orderPayment->payment_id = $payment['id'];
                    $orderPayment->stripe_date = json_encode($payment);
                    $order->orderPayment()->save($orderPayment);
                    print_r($orderPayment);

                    $newNotification = new \App\Notification;
                    $newNotification->title = 'Payment for the order #'.$order->shopify_order_id.' is successfull';
                    $newNotification->details = 'Payment for the <a href="'.url('storeOrders/'.$order->id).'"> Order #<'.$order->shopify_order_id.'</a> amount:'.$order->order_total.' AUD is successfull';

                    $user->notification()->save($newNotification);
                    print_r($newNotification);
                }else{
                    $newNotification = new \App\Notification;
                    $newNotification->title = 'Error in Payment';
                    $newNotification->details = 'Error in payment, Please check your details(Credentials,balance,etc) And add card. Thanks!';
                    $user->notification()->save($newNotification);
                    print_r($newNotification);
                    break;
                }
            }

        }
        die('done');
    }
}