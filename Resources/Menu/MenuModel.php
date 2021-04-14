<?php

namespace Resources\Menu;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class MenuModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "items";
  
	public function findItemsByRestaurantId($_id)
    {
    	$res = Capsule::table('items')->select('items.*', 'categories.name as category_name')
		    	->join('categories', 'items.category_id', '=', 'categories.id')
		    	->where('items.restaurant_id', '=', $_id)
		    	->get();
    	

		
		return $res;
    }
}