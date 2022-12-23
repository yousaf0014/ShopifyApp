<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
	protected $fillable = [
    	'order_id','first_name','last_name','address1','address2','phone','city','zip','province','country','company','latitude','longitude','country_code','province_code','name'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }
}