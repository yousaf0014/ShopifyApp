<?php
namespace App\Http\Controllers\Customer;

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

use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;
use App\User;
use App\Category;
use App\UserProduct;
use App\AdminProduct;
use App\PrintingGroup;
use App\ProductAttributeGroup;
use App\UserProductAttribute;
use App\UserProductPrintingGroupOption;
use App\UserProdcutVariant;
use App\StoreProduct;
use Auth;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $stores;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authUser = Auth::user()->id;
            $this->stores = User::where('parent_id',$authUser)->get();
            return $next($request);
        });
        
    }
    
    public function index(Request $request)
    {
        $selectedStore = $keyword = '';
        $auth = Auth::user();
        $productObj = new UserProduct;
        $userList[] = $auth->id;
        if(!empty($this->stores)){
            foreach($this->stores as $st){
                $userList[] = $st->id;
            }       
        }
        //$productObj = $productObj->where('user_id',$userList);
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $productObj = $productObj->where('title','like', '%'.$keyword.'%');
        }
        
        if(!empty($request->store)){
            $selectedStore = $request->store;
            $prodcutsList = StoreProduct::where('store_id',$selectedStore)->pluck('user_product_id');
            if(!empty($prodcutsList)){
                $productObj = $productObj->where(function($query) use ($selectedStore,$prodcutsList){
                    $query->orWhere('user_id', $selectedStore);
                    $query->orWhereIn('id',$prodcutsList);
                });
            }else{
                $productObj = $productObj->where('user_id', $selectedStore);
            }

        }else{
            $productObj = $productObj->whereIn('user_id',$userList);
        }
        $products = $productObj->paginate(20);
        $stores = $this->stores;
        return view('Customer.Products.index',compact('products','keyword','stores','selectedStore'));
    }

    public function create(Request $request, $category = null){
        $adminProductObj = new AdminProduct;
        if(!empty($category)){
            $adminProductObj = $adminProductObj->where('category_id',$category);
        }
        $adminProducts = $adminProductObj->paginate(20);
        $categories = Category::get();
        return View('Customer.Products.create',compact('adminProducts','categories'));
    }
    
    public function createProduct(Request $request, AdminProduct $adminProduct){
        $shop = Auth::user();
        $currency = $shop->currency.',AUD';
        $rate = getCurrency('live',$currency,'USD');
        $productAttributeGroups = $adminProduct->productAttributeGroup()->with('attribute')->get();
        $stores = $this->stores;
        return View('Customer.Products.createProduct',compact('adminProduct','productAttributeGroups','stores'));
    }

    public function createProductStep2(Request $request, AdminProduct $adminProduct){
        $data = $request->all();
        unset($data['_token']);
        $data['product_id'] = $adminProduct->id;
        $request->session()->forget('create_prodcut_data');
        $request->session()->put('create_prodcut_data', $data);
        $colorGroup;
        foreach($adminProduct->productAttributeGroup as $group){
            if($group->type == 'color'){
                $colorGroup = $group;
            }
        }
        $colors = $data['attribute'][$colorGroup->id];
        $colorData = \App\ProductAttributeGroupValue::whereIn('id',$colors)->get();
        $productPrintingGroups = $adminProduct->productPrintingGroup()->with('attribute')->get();
        return View('Customer.Products.createProduct2',compact('colorData','adminProduct','productPrintingGroups'));
    }

    public function createProductStep2Get(Request $request, AdminProduct $adminProduct){
        $productPrintingGroups = $adminProduct->productPrintingGroup()->with('attribute')->get();
        $data = $request->session()->get('create_prodcut_data', array());
        $colorGroup;
        foreach($adminProduct->productAttributeGroup as $group){
            if($group->type == 'color'){
                $colorGroup = $group;
            }
        }
        $colors = $data['attribute'][$colorGroup->id];
        $colorData = \App\ProductAttributeGroupValue::whereIn('id',$colors)->get();
        return View('Customer.Products.createProduct2',compact('colorData','adminProduct','productPrintingGroups'));
    }

    public function createProductStep2store(Request $request, AdminProduct $adminProduct){
        $data = $request->all();
        $oldData = $request->session()->get('create_prodcut_data', array());
        $request->session()->forget('create_prodcut_data');
        
        $orginalAmount = $adminProduct->price;
        $userProductObj = new \App\UserProduct;
        $userProductObj->name =  $oldData['name'];
        $userProductObj->details =  $oldData['details'];
        $userProductObj->sku =  $oldData['sku'];
        $userProductObj->admin_product_id =  $adminProduct->id;
        $userProductObj->user_id = Auth::user()->id;
        $userProductObj->product_pic = $adminProduct->product_pic;
        $price = $request->base_price + $request->profit;
        foreach($data['amount'] as $amt){
            $price += $amt;
        }
        $userProductObj->price = $price;
        $userProductObj->price_margin = $request->profit;
        $userProductObj->save();
        if(trim($oldData['sku']) == trim($adminProduct->sku)){
            $userProductObj->sku =  $oldData['sku'].'-'.$userProductObj->id;
            $userProductObj->save();
        }
        $colors = $data['color'];
        if(!empty($oldData['attribute'])){
            foreach($oldData['attribute'] as $groupID=>$groupData){
                foreach($groupData as $attribute){
                    $productAttribute = new UserProductAttribute;
                    $productAttribute->product_attribute_group_id = $groupID;
                    $productAttribute->product_attibute_group_value_id = $attribute;
                    $attribData = \App\ProductAttributeGroupValue::where('id',$attribute)->first();
                    $productAttribute->value = $attribData->value;
                    $productAttribute->art = !empty($colors[$attribute]) ? $colors[$attribute]:'';
                    $userProductObj->productAttribute()->save($productAttribute);    
                }
            }
        }

        $shirtDesign = array();
        if(!empty($data['shirtdesign'])){
           $shirtDesign = $request->file('shirtdesign'); 
        }
        $darkArtWorks = $artworks = array();
        if(!empty($data['art_light'])){
           $artworks = $request->file('art_light'); 
        }
        if(!empty($data['art_dark'])){
           $darkArtWorks = $request->file('art_dark'); 
        }

        if(!empty($data['printing'])){
            foreach($data['printing'] as $groupID=>$groupData){
                foreach($groupData as $attribute){
                    $attributData = \App\PrintingGroupAttribute::where('id',$attribute)->first();

                    $orginalAmount += $attributData->amount;
                    $productAttribute = new UserProductPrintingGroupOption;
                    $productAttribute->name = $attributData->name;
                    $productAttribute->printing_group_id = $groupID;
                    $productAttribute->printing_group_attribute_id = $attribute;
                    if(!empty($shirtDesign[$attribute])){
                        $file = $shirtDesign[$attribute];
                        $destinationPath = 'uploads/';
                        $file->store($destinationPath, ['disk' => 'public']);
                        $productAttribute->shirt_design = $file->hashName();
                    }
                    if(!empty($artworks[$attribute])){
                        $file = $artworks[$attribute];
                        $destinationPath = 'uploads/';
                        $file->store($destinationPath,['disk' => 'public']);
                        $productAttribute->artwork = $file->hashName();
                    }
                    if(!empty($darkArtWorks[$attribute])){
                        $file = $darkArtWorks[$attribute];
                        $destinationPath = 'uploads/';
                        $file->store($destinationPath, ['disk' => 'public']);
                        $productAttribute->artwork_dark = $file->hashName();
                    }
                    $userProductObj->productPrintingGroupOption()->save($productAttribute);
                }
            }
            
        }

        //$userProductObj->price = $price;
        if(strcmp($userProductObj->sku,$adminProduct) == 0){
            $userProductObj->sku = $userProductObj->sku.'-'.$userProductObj->id;
        }
        $userProductObj->charge_amount = $orginalAmount;
        $userProductObj->save();

        $stores = $oldData['stores'];
        foreach($stores as $st){
            $spObj = new StoreProduct;
            $spObj->store_id = !empty($st) ? $st:Auth::user()->id;
            $spObj->user_product_id = $userProductObj->id;
            $spObj->save();
        }
        $this->addProdcutToShopify();
        return redirect('/storeProducts');
    }

    private function __addProductShopify(&$up,$shop){
        $options1 = new Options();
        $options1->setVersion('2020-01');
        $options1->setApiKey(env('SHOPIFY_API_KEY'));
        $options1->setApiSecret(env('SHOPIFY_API_SECRET'));
        $api = new BasicShopifyAPI($options1);
        $api->setSession(new Session($shop->name, $shop->password, null));
        
        foreach($up->productAttribute as $pattr){
            $groups[$pattr->product_attribute_group_id] = $pattr->product_attribute_group_id;
            $groupAttributes[$pattr->product_attribute_group_id][] = $pattr->value;
        }
        $extra['title'] =  $up->name;
        $extra['sku'] = $up->sku;
        $amounts = localAmount($up->price,$up->currency);
        $extra['price'] = $amounts['amount'];
        $extra["presentment_prices"]= array( "price"=> array("currency_code"=> $shop->currency, "amount"=> $extra['price'] ));

        $varients = $this->__cartisenX($groupAttributes,$groups,$extra);
        $groupsData = \App\ProductAttributeGroup::whereIn('id', $groups)->get();
        $index = 1;
        foreach($groupsData as $gD){
            $temp = array();
            $temp['name'] = $gD->type == 'color' ? 'color':$gD->name;
            $temp['values'] = $groupAttributes[$gD->id];
            $temp['position'] = $index;
            $options[] =$temp; 
            $index++;
        }
        
        $product = array('product'=>
                    array(
                        'title'=>$up->name,'body_html'=>$up->details,'vendor'=>'Pixtraliya','product_type'=>'Custom T-Shirt Printed',
                        'variants'=> $varients,
                        'options'=>$options
                )
             );
       
        $sproduct = $api->rest('Post', '/admin/products.json',$product);
        $productID = $sproduct['body']['product']['id'];
        $productID = $sproduct['body']['product']['id'];
        $up->shopify_product_id = "{$productID}"; //$sproduct['body']['product']['id'];
        $up->save();
        $productVariantObj = new UserProdcutVariant;
        
        //$variantId = $varient['id'];
        $productVariantObj->options = '';
        $productVariantObj->shopify_variant_id = "{$productID}";
        $productVariantObj->type = "product";
        $up->productVarient()->save($productVariantObj);        

        $productVarients = array();
        
        if(!empty($sproduct['body']['product']['variants'])){
            foreach($sproduct['body']['product']['variants'] as $varient){
                $productVarients[] = $varient['id']; 
                $optionsArray = array();
                foreach($options as $gd){
                    $optionsArray[$gd['name']] = $varient['option'.$gd['position']]; 
                }

                $productVariantObj = new UserProdcutVariant;
                $variantId = $varient['id'];
                $productVariantObj->options = json_encode($optionsArray);
                $productVariantObj->shopify_variant_id = "{$variantId}";

                $up->productVarient()->save($productVariantObj);
            }  
        }
        $path = url('/').'/../storage/app/public/uploads/';
        $images = array();
        foreach($up->productPrintingGroupOption as $pics){
            /*if(!empty($pics->artwork)){
                $productImages = array('image'=>array('src'=>$path.$pics->artwork));
                $images[] = $api->rest('Post',"/admin/products/".$productID.'/images.json',$productImages);
            }*/
            if(!empty($pics->shirt_design)){
                $productImages = array('image'=>array('src'=>$path.$pics->shirt_design));
                $image = $api->rest('Post',"/admin/products/".$productID.'/images.json',$productImages);
                $images[] = $image['body']['image']['id'];
            }
        }
        if(!empty($productVarients) && !empty($images)){
            foreach($productVarients as $varnt){
                foreach($images as $img){
                    $temp = array();
                    $temp['variant']['id'] = $varnt;
                    $temp['variant']['image_id'] = $img;
                    $temp['fulfillment_service'] = 'OrderDeskOrderSync';
                    $response = $api->rest('Put','/admin/variants/'.$varnt.'.json',$temp);
                }
            }
        }
        return;
    }
    public function addProdcutToShopify(){
        $shop = Auth::user();
        ini_set("memory_limit","8196M");
        $userProdcuts = \App\UserProduct::where(function($query) {
                $query->orWhere('shopify_product_id', NULL)
                      ->orWhere('shopify_product_id', '');
            })->where('user_id',Auth::user()->id)->get();

        $options = $groups = $groupAttributes = $varients = array();
        foreach($userProdcuts as $up){
            $stores = StoreProduct::where('user_product_id',$up->id)->get();
            $userStores = array();
            if(!empty($stores)){
                $currentStores = array();
                foreach($stores as $ct){
                    $currentStores[] = $ct->store_id;
                }
                $userStores = User::whereIn('id',$currentStores)->get();
            }
            foreach($userStores as $us){
                if($us->type == 'shop'){
                    $this->__addProductShopify($up,$us);
                }
            }
            if(empty($up->shopify_product_id)){
                $up->shopify_product_id = "XXXXXXXX";
                $up->save();
            }
        }
        return redirect('/storeProducts');
    }

    private function __cartisenX($data,$keysArray,$extra=array()){
        $varients = array();
        $key = current($keysArray);
        $index = 1 ;
        foreach($data[$key] as $val){
            $varients[]['option'.$index] = $val;
        }
        $index++;
        unset($keysArray[$key]);
        while(!empty($keysArray)){
            $key = current($keysArray)*1;
            $thisData = $data[$key];
            unset($keysArray[$key]);
            $tempArray = array(); $innerIndex = 0;
            foreach($thisData as $data1){
                foreach($varients as $cart){
                    $tempArray[$innerIndex] = $cart;
                    $tempArray[$innerIndex++]['option'.$index] = $data1;
                }
            }
            $varients = $tempArray;
            $index++;
        }
        foreach($varients as &$val){
            $sku = ''; $title = ''; $extra['title'];   
            foreach($val as $opt){
                $title .= '-'.$opt;
                $sku .= '-'.$opt;
            }
            $val['title'] = $title;
            $val['sku'] = $extra['sku'].$sku;
            $val['price'] = $extra['price'];
            $val['presentment_prices'] = $extra['presentment_prices'];
            $title = $extra['title'];
            
        }
        return $varients;
    }
}