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
use App\ProductAttributeGroup;
use App\ProductAttributeGroupValue;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class ProductAttributesController extends Controller
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
        $pAGObj = new ProductAttributeGroup;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $pAGObj = $pAGObj->where('name','like', '%'.$keyword.'%');
        }        
        $groups = $pAGObj->paginate(20);
        return view('Admin.Groups.index',compact('groups','keyword'));
    }

    public function create(){
        return View('Admin.Groups.create');
    }

    public function store(Request $request){
        $rules = array(
            'name' => 'required|string',
            'attributes' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $pagObj = new ProductAttributeGroup;
            $pagObj->name = $data['name'];
            $pagObj->save();
            
            $attributes = explode(',', $data['attributes']);
            foreach($attributes as $attr){
                $newAttrObj = new ProductAttributeGroupValue;
                $newAttrObj->value = $attr;
                $pagObj->attribute()->save($newAttrObj);
            }
            flash('Successfully Saved.','success');
            return redirect('productAttributeGroups/'); 
        }
    }

    public function show(ProductAttributeGroup $productAttributeGroup)
    {
        return View('Admin.Groups.show',compact('productAttributeGroup'));   
    }
    

    public function edit(ProductAttributeGroup $productAttributeGroup){
        return View('Admin.Groups.edit',compact('productAttributeGroup'));
    }

    public function update(Request $request,ProductAttributeGroup $productAttributeGroup){
        $rules = array(
            'name' => 'required|string',
            'attributes' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $productAttributeGroup->name = $request->name;
            $productAttributeGroup->save();
            $productAttributeGroup->attribute()->delete();
            $data = $request->all();
            $attributes = explode(',', $data['attributes']);
            foreach($attributes as $attr){
                $OldAttr = $productAttributeGroup->attribute()->where('value',$attr)->withTrashed()->first();
                if(!empty($OldAttr->id)){
                    $OldAttr->restore();
                }else{
                    $attriObj = new ProductAttributeGroupValue;
                    $attriObj->value = $attr;
                    $productAttributeGroup->attribute()->save($attriObj);
                }
            }


            flash('Successfully updated Content!','success');
            return redirect('productAttributeGroups/');
        }
    }

    public function delete(ProductAttributeGroup $productAttributeGroup){
        $productAttributeGroup->attribute()->delete();
        $productAttributeGroup->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('productAttributeGroups/');
    }
}