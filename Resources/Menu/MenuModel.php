<?php

namespace Resources\Menu;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class MenuModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "items";
  	
  	protected $fillable = ["name", "description", "price", "discount", "is_available"];

  	public function findById($_id)
    {
    	$item = MenuModel::where("id", "=", $_id)->limit(1)->get();
		return $item;
	}

	public function findItemsByRestaurantId($_id)
    {
    	$res = Capsule::table('items')->select('items.*', 'categories.name as category_name')
		    	->join('categories', 'items.category_id', '=', 'categories.id')
		    	->where('items.restaurant_id', '=', $_id)
		    	->get();
    	

		
		return $res;
    }

    public function updatea($_id, $_input)
    {
        return MenuModel::where("items.id", "=", $_id)
                    ->update($_input);
    	
    }
}