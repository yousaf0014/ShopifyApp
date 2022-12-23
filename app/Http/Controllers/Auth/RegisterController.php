<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type'  => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $type = empty($data['type']) || $data['type'] == 'customer' ? 'customer':'order_desk';
        $password = Hash::make($data['password']);
        $partner_billing_id = sha1(strtotime('now').rand(1,100));
        do
        {   
            $user = User::where('partner_billing_id', $partner_billing_id)->first();
            $partner_billing_id = sha1(strtotime('now').rand(1,100));
        }
        while(!empty($user->id));
        
        $recipe_id = sha1(strtotime('now').rand(1,100));
        do
        {   
            $user1 = User::where('recipe_id', $recipe_id)->first();
            $recipe_id = sha1(strtotime('now').rand(1,100));
        }
        while(!empty($user1->id));


        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'type' => $type,
            'partner_billing_id' => $partner_billing_id,
            'recipe_id' => $recipe_id,
        ]);
    }

    protected function redirectTo()
    {
        $type = Auth::user()->type;
        if ($type == 'admin') {
            return '/adminwelcome';
        }else if($type == 'customer'){
            return '/customerwelcome';    
        }else if($type == 'order_desk'){
            return '/orderdeskwelcome';
        }
    }

}
