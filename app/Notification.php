<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'user_id','title','details'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

}