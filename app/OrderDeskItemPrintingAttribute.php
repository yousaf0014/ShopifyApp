<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class OrderDeskItemPrintingAttribute extends Model
{
	//use SoftDeletes;
	protected $table = 'order_desk_order_item_attributes';    
    protected $fillable = [
        'side','amount','mockup','design','order_desk_order_item_id'
    ];

    public function group(){
    	return $this->belongsTo(OrderDeskOrderItem::class);
    }

    

}
