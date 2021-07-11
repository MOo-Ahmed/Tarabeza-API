<?php

namespace Resources\Restaurant;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class RestaurantModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "restaurants";

	public function findById($_id)
    {
    	$res = RestaurantModel::where("id", "=", $_id)->limit(1)->get();
		return $res;
    }

}