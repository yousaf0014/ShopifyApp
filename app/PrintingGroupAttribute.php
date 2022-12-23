<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintingGroupAttribute extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'name','amount'
    ];

    public function group(){
    	return $this->belongsTo(PrintingGroup::class);
    }

}