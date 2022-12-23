<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{    
	protected $fillable = [
        'shipment_charges','additional_charge'
    ];
}