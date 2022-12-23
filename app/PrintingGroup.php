<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PrintingGroup extends Model
{    
    protected $fillable = [
        'name'
    ];

    public function attribute(){
    	return $this->hasMany(PrintingGroupAttribute::class);
    }

    public function products(){
        return $this->belongsToMany('\App\Product','product_printing_groups');
    }
}