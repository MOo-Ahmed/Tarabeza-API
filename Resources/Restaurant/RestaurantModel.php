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

}