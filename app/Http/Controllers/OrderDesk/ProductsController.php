<?php
namespace App\Http\Controllers\OrderDesk;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\User;
use App\Category;
use App\AdminProduct;
use App\PrintingGroup;
use App\ProductAttributeGroup;
use App\ProductAttributeGroupValue;

use Auth;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    public function categories(){
        $categoryObj = new Category;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $categoryObj = $categoryObj->where('title','like', '%'.$keyword.'%');
        }        
        $categories = $categoryObj->paginate(20);
        return view('OrderDesk.Products.categorylist',compact('categories','keyword'));
    }

    public function categorydetails(Request $request, Category $category){
        return view('OrderDesk.Products.showcategory',compact('category'));
    }
    public function index(Request $request,Category $category)
    {
         $keyword = '';
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $category = $category->adminProducts()->where('title','like', '%'.$keyword.'%');
        }        
        $products = $category->adminProducts()->orderBy('id', 'DESC')->paginate(20);
        return view('OrderDesk.Products.index',compact('category','products','keyword'));
    }

    public function show(Request $request,AdminProduct $adminProduct,Category $category)
    {
        $category = $adminProduct->category()->first();
        $printings = $attributes = array();
        foreach($adminProduct->productAttributeGroup as $group){
            $attributes[$group->id] = ProductAttributeGroupValue::where('product_attribute_group_id',$group->id)->get();
        }
        foreach($adminProduct->productPrintingGroup as $group){
            $printings[$group->id] = \App\PrintingGroupAttribute::where('printing_group_id',$group->id)->get();
        }

        return View('OrderDesk.Products.show',compact('category','adminProduct','printings','attributes'));
    }
}