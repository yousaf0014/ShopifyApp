<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeGroup extends Model
{    
    protected $fillable = [
        'name','type'
    ];

    public function attribute(){
    	return $this->hasMany(ProductAttributeGroupValue::class);
    }

    public function products(){
        return $this->belongsToMany('\App\Product','product_to_product_attribute_groups');
    }
}