<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class UserProductAttribute extends Model
{    
    protected $fillable = [
        'user_product_id', 'product_attribute_group_id','product_attibute_group_value_id','value','art'
    ];
}