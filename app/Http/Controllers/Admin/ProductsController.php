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
use App\AdminProduct;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\ProductAttributeGroup;
use App\ProductAttributeGroupValue;
use App\PrintingGroup;
use Illuminate\Http\UploadedFile;
use App\ColorGroup;
use App\ColorGroupValue;
use App\Repositories\OrderDeskApiClient;


class ProductsController extends Controller
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
    
    public function index(Request $request,Category $category)
    {
        
        $keyword = '';
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $category = $category->adminProducts()->where('title','like', '%'.$keyword.'%');
        }        
        $products = $category->adminProducts()->orderBy('id', 'DESC')->paginate(20);
        return view('Admin.Products.index',compact('category','products','keyword'));
    }

    public function create(Category $category){
        $groups = ProductAttributeGroup::where('type','others')->get();
        $printingGroups = PrintingGroup::get();
        $colorGroupsData = ColorGroup::get();
        $selected = '';
        $selectColors = array();
        return View('Admin.Products.create',compact('selectColors','colorGroupsData','category','groups','selected','printingGroups'));
    }

    public function store(Category $category,Request $request){
        $rules = array(
            'name' => 'required|string',   
            'details' => 'nullable',
            'supplier_code' =>  'required|string',
            'product_pic'   => 'required',
            'design_pic'=> 'nullable',
            'price' =>    'required|numeric',
            'attributeGroups'=>'required',
            'sku'   =>'required',
            'colors' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $adminProductObj = new AdminProduct;
            $adminProductObj->name = $request->name;
            $adminProductObj->details = $request->details;
            $adminProductObj->supplier_code = $request->supplier_code;
            $adminProductObj->sku = $request->sku;
            
            $file = $request->file('product_pic');
            $destinationPath = 'uploads/';
            $file->store($destinationPath, ['disk' => 'public']);
            $adminProductObj->product_pic = $file->hashName();

            if(!empty($request->file('design_pic'))){
                $file1 = $request->file('design_pic');
                $file1->store($destinationPath, ['disk' => 'public']);
                $adminProductObj->design_pic = $file1->hashName();
            }
            
            $adminProductObj->price = $request->price;
            $category->adminProducts()->save($adminProductObj);
            $colors = $request->colors;
            $attributeGroup = new ProductAttributeGroup;
            $attributeGroup->name = $request->name.' colors';
            $attributeGroup->type = 'color';
            $attributeGroup->save();
            $colorsData = ColorGroupValue::whereIn('id',$colors)->get();
            $colorsArr = array();
            foreach($colorsData as $cd){
                $productAttributeGroupValue = new productAttributeGroupValue;
                $productAttributeGroupValue->value = $cd->name;
                $productAttributeGroupValue->color_group_value_id = $cd->id;
                $attributeGroup->attribute()->save($productAttributeGroupValue);
                $colorsArr[] = $cd->name;
            }
            $adminProductObj->productAttributeGroup()->attach($attributeGroup->id);
            $attributeGroups = $request->attributeGroups;
            $varientArr['Color'] = implode(',',$colorsArr);
            foreach($attributeGroups as $group){
                $adminProductObj->productAttributeGroup()->attach($group);
                $arrtibutGroup = ProductAttributeGroup::where('id',$group)->first();
                $tempData = array();
                foreach($arrtibutGroup->attribute as $attr){
                    $tempData[] = $attr->value;
                }
                $varientArr[$arrtibutGroup->name] = mplode(',',$tempData);
            }
            
            $printingGroups = $request->printingGroups;
            foreach($printingGroups as $group){
                $adminProductObj->productPrintingGroup()->attach($group);
            }


            /* START adding product Invetoy to order Desk */

            $imageBaseUrl = url('/').'/../storage/app/public/uploads/';
            $od = new OrderDeskApiClient(env('ORDER_DESK_STORE_ID'), env('ORDER_DESK_STORE_API_KEY'));
            $args = array(
              "name" => $adminProductObj->name,
              "code" => $adminProductObj->supplier_code,
              "price" => $adminProductObj->price,
              "stock" => 100,
              "variation_list" =>$varientArr,
              "metadata" => array(
                "image" => $imageBaseUrl.$adminProductObj->product_pic,
                "print_sku" => $adminProductObj->sku,
              ),
            );
            $result = $od->post("inventory-items", $args);
            $adminProductObj->order_desk_id = $result['inventory_item']['id'];
            $adminProductObj->save();
            /* END adding product Invetoy to order Desk */

            flash('Successfully Saved.','success');
            return redirect('adminProducts/'.$category->id); 
            
        }
    }

    public function show(AdminProduct $adminProduct)
    {
        $category = $adminProduct->category()->first();
        $printings = $attributes = array();
        foreach($adminProduct->productAttributeGroup as $group){
            $attributes[$group->id] = ProductAttributeGroupValue::where('product_attribute_group_id',$group->id)->get();
        }
        foreach($adminProduct->productPrintingGroup as $group){
            $printings[$group->id] = \App\PrintingGroupAttribute::where('printing_group_id',$group->id)->get();
        }

        return View('Admin.Products.show',compact('category','adminProduct','printings','attributes'));
    }

    public function fileview(Request $request,Category $category)
    {
        return View('Admin.Products.file',compact('category'));   
    }

    /*
    * input is .csv file
    *   columns that need to be imported are 
    *  code, sku, name, desc, attribute group(comma seprated),printing group(comma seprated),blank price, image url, category
    */
    public function fileupload(Request $request,Category $category)
    {
        $defaultCatObj = $category;
        $file = $request->file('admin_products');
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $content = $file->get();
        $your_array = explode("\n", $content);
        $header = explode(',',$your_array[0]);
        
        $tableHeader = array('code'=>'supplier_code','sku'=>'sku','name'=>'name','description'=>'details','blank price'=>'price','main_image'=>'product_pic','attribute_group'=>'attribute_group','printint_group'=>'printint_group','category'=>'category','color_group'=>'color_group','colors'=>'colors');
        $finalAttr = array();
        $categoryIndex = $skuIndex = null;
        foreach($header as $index=>$head){
            if(strtolower($head) == 'category'){
                $categoryIndex = $index;
            }else if(!empty($tableHeader[strtolower(trim($head))])){
                $finalAttr[$index] = $tableHeader[strtolower(trim($head))];
            }
            if(strtolower($head) == 'sku'){
                $skuIndex = $index;
            }
        }
        unset($your_array[0]);
        if(empty($skuIndex)){
            return Redirect::back()->withInput($request->all())->withErrors(array('sku'=>'sku not found'));
        }
        foreach($your_array as $line){
            $cat = preg_split('/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $line,-1,PREG_SPLIT_DELIM_CAPTURE);
            
            if(!empty($cat[$categoryIndex])){
                $catgoryText = trim($cat[$categoryIndex]); 
                if(!empty($catgoryText)){
                    $findCatObj = Category::where('title',$catgoryText)->first();
                    if(empty($findCatObj->id)){
                        $findCatObj =  new Category;
                        $findCatObj->title = $catgoryText;
                        $findCatObj->save();
                    }
                    $defaultCatObj = $findCatObj;
                }
            }
            //$cat = explode(',', $line);
            //$output = preg_split('"[^"]*"|[^,]+',$line);
            
            if(!empty($cat[$skuIndex])){
                $colorGroup = $colors = $printing = $attributes = '';
                $adminProductObj = AdminProduct::where('sku',$cat[$skuIndex])->first();
                if(empty($adminProductObj->sku)){
                    $adminProductObj = new AdminProduct;
                }
                foreach($finalAttr as $index=>$attr){
                    if($attr == 'printint_group'){
                        $printing = str_replace('"', '', $cat[$index]);
                    }else if($attr == 'attribute_group'){
                        $attributes = str_replace('"', '', $cat[$index]);
                    }else if($attr == 'color_group'){
                        $colorGroup = str_replace('"', '', $cat[$index]);
                    }else if($attr == 'colors'){
                        $colors = str_replace('"', '', $cat[$index]);
                    }else{
                        $val = str_replace('"', '', $cat[$index]);
                        $adminProductObj->$attr = $val;
                    }
                }
                if(!empty($adminProductObj->product_pic)){
                    
                    $info = pathinfo($adminProductObj->product_pic);
                    /*$ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, trim($adminProductObj->product_pic));
                    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
                    $contents = curl_exec($ch);
                    $codes = curl_getinfo($ch);
                    echo '<pre>';
                    print_r($codes);
                    print_r(curl_error($ch));
                    curl_close ($ch);
                    
                    exit; */
                    $contents = @file_get_contents($adminProductObj->product_pic);
                    $file = '/tmp/' . $info['basename'];
                    file_put_contents($file, $contents);
                    $uploaded_file = new UploadedFile($file, $info['basename']);
                    $destinationPath = 'uploads/';
                    $uploaded_file->store($destinationPath, ['disk' => 'public']);
                    $adminProductObj->product_pic = $uploaded_file->hashName();
                    $adminProductObj->product_pic;
                }
                $defaultCatObj->adminProducts()->save($adminProductObj);
                //$adminProductObj->save();
                $adminProductObj->productAttributeGroup()->detach();
                $adminProductObj->productPrintingGroup()->detach();
                
                $colorGroupObj = ColorGroup::where('name',$colorGroup)->first();
                if(!empty($colorGroupObj->id)){
                    $colorsData = explode(',', $colors);
                    $dbColors = ColorGroupValue::where('color_group_id',$colorGroupObj->id)->whereIn('name',$colorsData)->get();
                    if(!empty($dbColors[0]->id)){
                        $attributeGroup = new ProductAttributeGroup;
                        $attributeGroup->name = $adminProductObj->name.' colors';
                        $attributeGroup->type = 'color';
                        $attributeGroup->save();
                        $adminProductObj->productAttributeGroup()->attach($attributeGroup->id);
                        foreach($dbColors as $cd){
                            $productAttributeGroupValue = new productAttributeGroupValue;
                            $productAttributeGroupValue->value = $cd->name;
                            $productAttributeGroupValue->color_group_value_id = $cd->id;
                            $attributeGroup->attribute()->save($productAttributeGroupValue);
                        }
                    }  
                }
                if(!empty($printing)){
                    $printingArr = explode(',', $printing);
                    foreach($printingArr as $print){
                        $printingFind = PrintingGroup::where('name',$print)->first();
                        if(empty($printingFind->id)){
                            $printingFind = new  PrintingGroup;
                        }
                        $printingFind->name = $print;
                        $printingFind->save();
                        $adminProductObj->productPrintingGroup()->attach($printingFind->id);
                    }   
                }
                if(!empty($attributes)){
                    $attributArr = explode(',', $attributes);
                    foreach($attributArr as $attr){
                        $attrFind = ProductAttributeGroup::where('name',$attr)->first();
                        if(empty($attrFind->id)){
                            $attrFind = new  ProductAttributeGroup;
                        }
                        $attrFind->name = $attr;
                        $attrFind->save();
                        $adminProductObj->productAttributeGroup()->attach($attrFind->id);
                    }   
                }
            }    
        }
        flash('Successfully uploaded the Content!','success');
        return redirect('adminProducts/'.$category->id);
    }

    public function edit(Request $request,AdminProduct $adminProduct){
        $category  = $adminProduct->category()->first();
        $selectedPrintings = $selected = array();
        $selectColors = array();
        foreach($adminProduct->productAttributeGroup as $group){
            if($group->type == 'others'){
                $selected[$group->id] = $group->id;
            }else{
                $selectColors = ProductAttributeGroupValue::where('product_attribute_group_id',$group->id)->pluck('color_group_value_id')->toArray();
            }
        }
        foreach($adminProduct->productPrintingGroup as $pg){
            $selectedPrintings[$pg->id] = $pg->id;
        }
        $groups = ProductAttributeGroup::where('type','others')->get();
        $printingGroups = PrintingGroup::get();
        $colorGroupsData = ColorGroup::get();
        return View('Admin.Products.edit',compact('adminProduct','colorGroupsData','category','selectColors','selected','groups','printingGroups','selectedPrintings'));
    }

    public function update(Request $request,AdminProduct $adminProduct){
        $rules = array(
            'name' => 'required|string',
            'details' => 'nullable',
            'supplier_code' =>  'required|string',
            'product_pic'   => 'nullable',
            'design_pic'=> 'nullable',
            'price' =>    'required|numeric',
            'attributeGroups'=>'required',
            'sku'   =>'required',
            'colors'    => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $colorGroup;
            $productAttributes = $adminProduct->productAttributeGroup()->get();
            foreach($adminProduct->productAttributeGroup as $group){
                //print_r($group);
                if($group->type == 'color'){
                    $colorGroup = $group;
                }
            }
            $colorGroup->attribute()->delete();
            $data = $request->all();
            $attributeGroups = $request->attributeGroups;
            
            unset($data['_method']);unset($data['_token']); 
            unset($data['attributeGroups']);
            unset($data['printingGroups']);
            unset($data['colors']);
            foreach($data as $field=>$value){ 
                $adminProduct->$field = $value;                
            }

            if(!empty($request->file('product_pic'))){
                $file = $request->file('product_pic');
                $destinationPath = 'uploads/';
                $file->store($destinationPath, ['disk' => 'public']);
                $adminProduct->product_pic = $file->hashName();
            }
            if(!empty($request->file('design_pic'))){
                $file1 = $request->file('design_pic');
                $file1->store($destinationPath, ['disk' => 'public']);
                $adminProduct->design_pic = $file1->hashName();
            }
            $adminProduct->save();
            $adminProduct->productAttributeGroup()->detach();
            $adminProduct->productPrintingGroup()->detach();

            $adminProduct->productAttributeGroup()->attach($colorGroup->id);
            $colorsData = ColorGroupValue::whereIn('id',$request->colors)->get();
            foreach($colorsData as $cd){
                $productAttributeGroupValue = new productAttributeGroupValue;
                $productAttributeGroupValue->value = $cd->name;
                $productAttributeGroupValue->color_group_value_id = $cd->id;
                $colorGroup->attribute()->save($productAttributeGroupValue);
            }
            
            foreach($attributeGroups as $group){
                $adminProduct->productAttributeGroup()->attach($group);
            }
            
            $printingGroups = $request->printingGroups;
            foreach($printingGroups as $group){
                $adminProduct->productPrintingGroup()->attach($group);
            }
            flash('Successfully updated Content!','success');
            return redirect('adminProducts/'.$adminProduct->category_id);
        }
    }

    public function delete(Category $category,AdminProduct $adminProduct){
        $adminProduct->productAttributeGroup()->detach();
        $adminProduct->productPrintingGroup()->detach();
        $category = $adminProduct->Category()->first();
        $adminProduct->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('adminProducts/'.$category->id);
    }
    
}