<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model
{    
    protected $fillable = [
        'name'
    ];

    public function attribute(){
    	return $this->hasMany(ColorGroupValue::class);
    }
}