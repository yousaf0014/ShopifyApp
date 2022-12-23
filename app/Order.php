<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'user_id','name','email','shopify_order_id','order_date','items','country','status','shipment','additional_shipment','order_total','data','quantity','charge','type'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function orderItem(){
        return $this->hasMany('\App\OrderItem');
    }

    public function orderDeskOrderItem(){
        return $this->hasMany('\App\OrderDeskOrderItem');
    }    
    
    public function orderShipment(){
        return $this->hasOne('\App\OrderShipment');
    }
    public function orderPayment(){
        return $this->hasOne('\App\OrderPayment');
    }


}