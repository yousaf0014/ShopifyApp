<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProduct extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'user_id','category_id','name', 'sku','details','supplier_code','product_pic','design_pic','price','shopify_product_id','charge_amount'
    ];

    public function adminProduct(){
    	return $this->belongsTo(AdminProduct::class);
    }

    public function productAttribute(){
        return $this->hasMany('\App\UserProductAttribute');
    }

    /*public function productAttributeGroupValue(){
        return $this->hasMany('\App\ProductAttributeGroupValue');
    }*/

    
    public function productPrintingGroupOption(){
        return $this->hasMany('\App\UserProductPrintingGroupOption');
    }

    public function productVarient(){
        return $this->hasMany('\App\UserProdcutVariant');
    }


    /*
    public function prodcutPrintingGroupAttribute(){
        return $this->belongsToMany('\App\PrintingGroupAttribute','user_product_printing_options');
    }*/

}