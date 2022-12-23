<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'order_id','title','sku','name','quantity','attributes','shopify_product_id','shopify_parent_id','order_itme_id','user_product_id','status','price'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }
}