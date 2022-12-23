<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $fillable = [
        'product_id','store_id'
    ];
}