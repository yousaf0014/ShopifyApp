<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id','user_id','amount','payment_id','stripe_date'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }
}