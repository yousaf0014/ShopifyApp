<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminProduct extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'category_id','name', 'sku','details','supplier_code','product_pic','design_pic','price','order_desk_id'
    ];

    public function category(){
    	return $this->belongsTo(Category::class);
    }

    public function productAttributeGroup(){
        return $this->belongsToMany('\App\ProductAttributeGroup','product_to_product_attribute_groups');
    }
    public function productPrintingGroup(){
        return $this->belongsToMany('\App\PrintingGroup','product_printing_groups');
    }

}