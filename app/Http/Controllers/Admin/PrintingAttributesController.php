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
use App\PrintingGroup;
use App\PrintingGroupAttribute;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class PrintingAttributesController extends Controller
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
        $prGObj = new PrintingGroup;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $prGObj = $prGObj->where('name','like', '%'.$keyword.'%');
        }        
        $groups = $prGObj->paginate(20);
        return view('Admin.Printing.index',compact('groups','keyword'));
    }

    public function create(){
        $printingGroup = null;
        return View('Admin.Printing.create',compact('printingGroup'));
    }

    public function store(Request $request){
        $rules = array(
            'name' => 'required|string',
            'attributes' =>'required|array',
            'amounts' => 'required|array'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $pagObj = new PrintingGroup;
            $pagObj->name = $data['name'];
            $pagObj->save();
            
            $attributes = $data['attributes'];
            $amounts = $data['amounts'];
            foreach($attributes as $index=>$attr){
                if(!empty($attr)){
                    $newAttrObj = new PrintingGroupAttribute;
                    $newAttrObj->name = $attr;
                    $newAttrObj->amount = $amounts[$index];
                    $pagObj->attribute()->save($newAttrObj);
                }
            }
            flash('Successfully Saved.','success');
            return redirect('printingGroups/'); 
        }
    }

    public function show(PrintingGroup $printingGroup)
    {
        return View('Admin.Printing.show',compact('printingGroup'));   
    }
    

    public function edit(PrintingGroup $printingGroup){
        return View('Admin.Printing.edit',compact('printingGroup'));
    }

    public function update(Request $request,PrintingGroup $printingGroup){
        $rules = array(
            'name' => 'required|string',
            'attributes' =>'required|array',
            'amounts' => 'required|array'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $printingGroup->name = $request->name;
            $printingGroup->save();
            $printingGroup->attribute()->delete();
            $data = $request->all();
            $attributes = $data['attributes'];
            $amounts = $data['amounts'];
            foreach($attributes as $index=>$attr){
                if(!empty($attr)){
                    $OldAttr = $printingGroup->attribute()->where('name',$attr)->withTrashed()->first();
                    if(!empty($OldAttr->id)){
                        $OldAttr->restore();
                        $OldAttr->name = $attr;
                        $OldAttr->amount = $amounts[$index];
                        $OldAttr->save();
                    }else{
                        $newAttrObj = new PrintingGroupAttribute;
                        $newAttrObj->name = $attr;
                        $newAttrObj->amount = $amounts[$index];
                        $printingGroup->attribute()->save($newAttrObj);
                    }
                }
            }

            flash('Successfully updated Content!','success');
            return redirect('printingGroups/');
        }
    }

    public function delete(PrintingGroup $printingGroup){
        $printingGroup->attribute()->delete();
        $printingGroup->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('printingGroups/');
    }
}