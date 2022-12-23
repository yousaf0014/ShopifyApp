<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use Auth;
use App\Setting;
use App\CountryCode;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(array('auth','admin'));
    }
    
     public function index(Request $request)
    {
        $settingObj = new Setting;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $settingObj = $settingObj->where('name','like', '%'.$keyword.'%');
        }        
        $settings = $settingObj->paginate(20);
        return view('Admin.Settings.index',compact('settings','keyword'));
    }

    public function create(){
        return View('Admin.Settings.create');
    }

    public function store(Request $request){
        $rules = array(
            'name' => 'required|string',
            'value' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $settingObj = new Setting;
            $cat = $settingObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('settings/'); 
        }
    }

    public function show(Setting $setting)
    {
        return View('Admin.Settings.show',compact('setting'));   
    }

    public function edit(Setting $setting){
        return View('Admin.Settings.edit',compact('setting'));
    }

    public function update(Request $request,Setting $setting){
        $rules = array(
            'name' => 'required|string',
            'value' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $setting->delete();
            $settingObj = new Setting;
            $settingObj->name = $request->name;
            $settingObj->value = $request->value;
            $settingObj->save();
            flash('Successfully updated Content!','success');
            return redirect('settings/');
        }
    }

    public function delete(Setting $setting){
        $setting->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('settings/');
    }

    public function listShipmentCountries(Request $request){
        $countryCodeObj = new CountryCode;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $countryCodeObj = $countryCodeObj->where('country','like', '%'.$keyword.'%');
        }
        $cc = $countryCodeObj->paginate(20);
        return view('Admin.ShipmentCountries.index',compact('cc','keyword'));
    }

    public function editShipment(Request $request,CountryCode $countryCode){
        return view('Admin.ShipmentCountries.edit',compact('countryCode'));
    }

    public function updateShipment(Request $request,CountryCode $countryCode){
        $countryCode->shipment_charges = $request->shipment_charges;
        $countryCode->additional_charge = $request->additional_charge;
        $countryCode->save();
        return redirect('shipment/');
    }
}