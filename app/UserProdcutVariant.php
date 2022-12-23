<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class UserProdcutVariant extends Model
{    
    protected $fillable = [
        'user_product_id', 'shopify_variant_id','options','type'
    ];

    public function userProduct(){
    	return $this->belongsTo(UserProduct::class);
    }
}