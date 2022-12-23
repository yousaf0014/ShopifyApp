<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cartalyst\Stripe\Stripe;
use App\UserPaymentMethod;
use App\User;
use App\Order;
use DB;

class paymentCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cron';

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
        \Log::info("Payment Cron Start");
        $stripe = new Stripe(env('STRIPE_SECRECT'));
        $paymentObj = new UserPaymentMethod;
        $pauymentMethods1 = DB::table('users')->get();
        $pauymentMethods = $paymentObj->get();
        //echo '<pre>';
        //print_r($pauymentMethods);
        foreach($pauymentMethods as $intent){
            $userID = $intent->user_id;
            $user = User::where('id',$userID)->first();
            $shops = User::where('parent_id',$userID)->get();
            $usersArr[] = $userID;
            foreach($shops as $sh){
                $usersArr[] = $sh->id;
            }
            //print_r($usersArr);
            $orders = Order::whereIn('user_id',$usersArr)->where('status','payment')->get();
            //print_r($orders);
            foreach($orders as $order){
                //echo '<br>=============='.$order->order_total.'====================<br>';
                $payment = $stripe->paymentIntents()->create([
                    'amount' => floatval($order->order_total),
                    'currency' => 'aud',
                    'customer'  => $user->stripe_id,
                    'payment_method'=> $intent->method_id,
                    'off_session'=>true,
                    'confirm'=>true,
                    'receipt_email'=>$user->email,
                ]);
                //print_r($payment);
                $orderUser = User::where('id',$order->user_id)->first();
                
                if($payment['status'] == 'succeeded'){
                    $order->status = 'approved';
                    $order->save();
                    if($order->type == 'order_desk'){
                        $data = json_decode($order->data);
                        $data['status'] = 'in_progress';
                        $order->data = json_encode($data);
                        $order->save();
                    }

                    $orderPayment = new \App\OrderPayment;
                    $orderPayment->amount = $order->order_total;
                    $orderPayment->user_id = $order->user_id;
                    $orderPayment->payment_id = $payment['id'];
                    $orderPayment->stripe_date = json_encode($payment);
                    $savedPayment = $order->orderPayment()->save($orderPayment);
                    //print_r($orderPayment);

                    $url = url('storeOrders/'.$order->id);
                    $url = str_replace('http://', 'https://', $url);
                    $newNotification = new \App\Notification;
                    $newNotification->title = 'Payment for the order #'.$order->shopify_order_id.' is successfull';
                    $newNotification->details = 'Payment for the <a href="'.$url.'"> Order #'.$order->shopify_order_id.'</a> amount:'.$order->order_total.' AUD is successfull';

                    $orderUser->notification()->save($newNotification);
                    //print_r($newNotification);
                }else{
                    $newNotification = new \App\Notification;
                    $newNotification->title = 'Error in Payment';
                    $newNotification->details = 'Error in payment, Please check your details(Credentials,balance,etc) And add card. Thanks!';
                    $orderUser->notification()->save($newNotification);
                    //print_r($newNotification);
                    break;
                }
            }
        }
        \Log::info("Payment Cron END");
        die('done');
    }
}
