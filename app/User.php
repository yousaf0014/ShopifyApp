<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;
use App\Notifications\OrderCreated;
class User extends Authenticatable implements IShopModel
{
    use Notifiable;
    use ShopModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','type','currency','stripe_id','shopify_location_id','recipe_id','partner_billing_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function paymentMethod(){
        return $this->hasMany(UserPaymentMethod::class);
    }

    public function notification(){
        return $this->hasMany(Notification::class);
    }

    public function sendOrderCreationNotification($order)
    {
        $response = $this->notify(new OrderCreated($this,$order)); // my notification
    }
}