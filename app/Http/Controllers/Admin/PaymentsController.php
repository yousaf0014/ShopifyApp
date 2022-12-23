<?php
namespace App\Http\Controllers\Admin;

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
    public function __construct()
    {
               
    }
    
    public function index(Request $request)
    {
        $users = User::get();
        $userNames = array();
        foreach($users as $st){
            $userNames[$st->id] = $st->name; 
        }
        $payments = \App\OrderPayment::orderBy('id','Desc')->paginate(20);
        return view('Admin.Payments.index',compact('payments','userNames'));
    }
}