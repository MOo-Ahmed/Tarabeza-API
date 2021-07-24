<?php

namespace Resources\Restaurant;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class RestaurantModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "restaurants";

    protected $fillable = ['name', "logo_url", "contact_number", "address", "longitude", "latitude", "reservation_active", "opening_time", "closing_time"];

	public function findById($_id)
    {
    	$res = RestaurantModel::where("id", "=", $_id)->limit(1)->get();
		return $res;
	}

	public function updatea($_id, $_input)
    {
        return RestaurantModel::where("restaurants.id", "=", $_id)
                    ->update($_input);
    	
    }
    
    public function findReservationsByRestaurantId($_id)
    {
    	$res = Capsule::table('reservations')->select('reservations.*',
                                     'users.first_name AS first_name',
                                    'users.last_name AS last_name',
                                    'restaurants.name AS restaurant_name')
		    	->join('customers', 'customers.id', '=', 'reservations.customer_id')
		    	->join('users', 'users.id', '=', 'customers.user_id')
                ->join('restaurants', 'restaurants.id', '=', 'reservations.restaurant_id')
		    	->where('reservations.restaurant_id', '=', $_id)
		    	->get();
    	

		
		return $res;
    }
}