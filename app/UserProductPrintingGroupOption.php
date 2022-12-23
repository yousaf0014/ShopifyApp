<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class UserProductPrintingGroupOption extends Model
{    
	protected $table = 'user_product_printing_options';
    protected $fillable = [
    	'user_product_id','printing_group_id','printing_group_attribute_id','artwork','shirt_design','name','artwork_dark'
    ];

    

    
}