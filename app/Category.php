<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{    
    protected $fillable = [
        'title', 'details'
    ];

    public function adminProducts(){
    	return $this->hasMany(AdminProduct::class);
    }

}