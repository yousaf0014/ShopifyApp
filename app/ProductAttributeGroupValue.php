<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeGroupValue extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'value','product_attribute_group_values'
    ];

    public function group(){
    	return $this->belongsTo(ProductAttributeGroup::class);
    }

}