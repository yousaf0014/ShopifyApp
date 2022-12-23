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
use App\Category;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class CategoriesController extends Controller
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
        $categoryObj = new Category;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $categoryObj = $categoryObj->where('title','like', '%'.$keyword.'%');
        }        
        $categories = $categoryObj->paginate(20);
        return view('Admin.Categories.index',compact('categories','keyword'));
    }

    public function create(){
        return View('Admin.Categories.create');
    }

    public function store(Request $request){
        $rules = array(
            'title' => 'nullable|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $categoryObj = new Category;
            $cat = $categoryObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('categories/'); 
        }
    }

    public function show(Category $category)
    {
        return View('Admin.Categories.show',compact('category'));   
    }

    public function fileview(Request $request)
    {
        return View('Admin.Categories.file');   
    }

    public function fileupload(Request $request)
    {
        $file = $request->file('colors');
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $content = $file->get();
        $your_array = explode("\n", $content);
        foreach($your_array as $line){
            $cat = explode(',', $line);
            if(!empty($cat[0])){
                $categoryObj = new Category;
                $find = Category::where('title',$cat[0])->first();
                if(empty($find->title)){
                    $categoryObj->title = $cat[0];
                    $categoryObj->details = $cat[1];
                    $categoryObj->save();
                }else{
                    $find->details = $cat[1];
                    $find->save();
                }
            }    
        }
        flash('Successfully uploaded the Content!','success');
        return redirect('categories/');
    }

    public function edit(Category $category){
        return View('Admin.Categories.edit',compact('category'));
    }

    public function update(Request $request,Category $category){
        $rules = array(
            'title' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            unset($data['_method']);unset($data['_token']); 
            foreach($data as $field=>$value){ 
                $category->$field = $value;                
            }
            $category->save();
            flash('Successfully updated Content!','success');
            return redirect('categories/');
        }
    }

    public function delete(Category $category){
        $category->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('categories/');
    }
}