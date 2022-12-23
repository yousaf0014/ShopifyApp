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
use App\ColorGroup;
use App\ColorGroupValue;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class ColorsController extends Controller
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
        $cAGObj = new ColorGroup;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $cAGObj = $cAGObj->where('name','like', '%'.$keyword.'%');
        }        
        $colors = $cAGObj->paginate(20);
        return view('Admin.Colors.index',compact('colors','keyword'));
    }

    public function create(){
        return View('Admin.Colors.create');
    }

    public function store(Request $request){
        $rules = array(
            'name' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $colorObj = new ColorGroup;
            $colorObj->name = $data['name'];
            $colorObj->save();
            
            foreach($data['hash'] as $index=>$attr){
                $colorAttrObj = new ColorGroupValue;
                $colorAttrObj->hash = $attr;
                $colorAttrObj->name = $data['color'][$index];
                $colorObj->attribute()->save($colorAttrObj);
            }
            flash('Successfully Saved.','success');
            return redirect('colorGroups/'); 
        }
    }

    public function show(ColorGroup $colorGroup)
    {
        return View('Admin.Colors.show',compact('colorGroup'));   
    }
    

    public function edit(ColorGroup $colorGroup)
    {
        return View('Admin.Colors.edit',compact('colorGroup'));
    }
    
    public function fileview(Request $request)
    {
        return View('Admin.Colors.file');   
    }

    public function fileupload(Request $request)
    {
        $rules = array(
            'colors' => 'required|mimes:csv,txt'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $file = $request->file('colors');
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $content = $file->get();
        $your_array = explode("\n", $content);
        unset($your_array[0]);
        foreach($your_array as $line){
            if(!empty($line)){
                $row = explode(',', $line);
                if(empty($row[0])){
                    continue;
                }
                $group = ColorGroup::where('name',trim($row['0']))->first();
                if(empty($group)){
                    $group = new ColorGroup;
                    $group->name = $row[0];
                    $group->save();
                }

                $colorGroupValueObj = new ColorGroupValue;
                $colorGroupValueObj->name = trim($row[1]);
                $colorGroupValueObj->hash = trim($row[2]);
                $group->attribute()->save($colorGroupValueObj);
            }
        }
        flash('Successfully uploaded the Content!','success');
        return redirect('colorGroups/');
    }
    public function update(Request $request,ColorGroup $colorGroup){
        $rules = array(
            'name' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $colorGroup->name = $request->name;
        $colorGroup->save();
        $colorGroup->attribute()->delete();
        $data = $request->all();
        foreach($data['hash'] as $index=>$attr){
            $colorAttrObj = new ColorGroupValue;
            $colorAttrObj->hash = $attr;
            $colorAttrObj->name = $data['color'][$index];
            $colorGroup->attribute()->save($colorAttrObj);
        }
        flash('Successfully updated Content!','success');
        return redirect('colorGroups/');
    }

    public function delete(ColorGroup $colorGroup){
        $colorGroup->attribute()->delete();
        $colorGroup->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('colorGroups/');
    }
}