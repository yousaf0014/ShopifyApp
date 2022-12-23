<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPaymentMethod extends Model
{
	use SoftDeletes;    
    protected $fillable = [
        'user_id', 'method_id'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

}