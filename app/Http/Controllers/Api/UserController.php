<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;

class UserController extends BaseController 
{   
    function index(Request $request){
        $user = Auth::user();
        $userData['name'] = $user->name;
        $userData['recipe_id'] = $user->recipe_id;
        $userData['partner_billing_id'] = $user->partner_billing_id;
        return $this->sendResponse($userData);
    }
}