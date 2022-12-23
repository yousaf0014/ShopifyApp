<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDeskOrderItem extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'order_id','name','quantity','order_desk_id','base_price','quantity','admin_supplier_code','admin_product_id','delivery_type','varient_list','total_price','status','attributes'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }

    public function attributes(){
        return $this->hasMany('\App\OrderDeskItemPrintingAttribute');
    }
}