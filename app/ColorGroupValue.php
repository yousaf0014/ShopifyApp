<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColorGroupValue extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'hash','name'
    ];

    public function group(){
    	return $this->belongsTo(ColorGroup::class);
    }

}