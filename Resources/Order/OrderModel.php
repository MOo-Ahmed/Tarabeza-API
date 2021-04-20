<?php

namespace Resources\Order;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class OrderModel extends \Illuminate\Database\Eloquent\Model
{
  
	public function insertOrder($_input)
    {
		//First insert the order
		$res = Capsule::table('orders')->insertGetId(array('customer_id' => $_input['customer_id'],
		 'restaurant_id' => $_input['restaurant_id'], 'table_id' => $_input['table_id']));
		
		//Get the length of the items array
		$numOfItems = count($_input['items']);

		//Then insert theses items 
        for ($i = 0 ; $i < $numOfItems ; $i++) {
            Capsule::table('order_details')->insert(array('order_id' => $res,
				'meal_id' => $_input['items'][$i]['meal_id'], 'quantity' => $_input['items'][$i]['quantity'],
				'comment' => $_input['items'][$i]['comment'], 'total' => $_input['items'][$i]['total']));
        }
		
		 return $res;
	}

	public function finish($_input)
  	{
		$res = Capsule::table('orders')->where('id', '=', $_input['order_id'])
			->update(array('is_finished' => 1));

		return $res;
  	}

	  public function pay($_input)
  	{
		$res = Capsule::table('orders')->where('id', '=', $_input['order_id'])
			->update(array('is_paid' => 1));

		return $res;
  	}
	
}