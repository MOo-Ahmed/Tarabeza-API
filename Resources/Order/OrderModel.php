<?php

namespace Resources\Order;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class OrderModel extends \Illuminate\Database\Eloquent\Model
{
  
	public function create($_input)
    {				
		//First insert the order
		$res = Capsule::table('orders')->insertGetId(array(
			'customer_id' 	=> $_input['customer_id'],
			'restaurant_id' => $_input['restaurant_id'], 
			'table_number' 	=> $_input['table_number'],
		 	'table_id' 		=> $_input['table_id']));
		
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

	public function approve($_input)
  	{
		$res = Capsule::table('orders')->where('id', '=', $_input['order_id'])
			->update(array('is_approved' => 1));

		return $res;
	}
	  
	public function cancel($_input)
  	{
		$res = Capsule::table('orders')->where('id', '=', $_input['order_id'])
			->update(array('is_deleted' => 1));

		return $res;
	}

	public function getNonFinished($selector, $id){
		
		$res = Capsule::table('orders')->select('orders.*', 'items.name as item_name',
								'order_details.quantity as qty', 
								'order_details.total as total')
				->where('orders.is_finished', '=', 0)
				->where($selector, '=', $id)
				->join('order_details', 'orders.id', '=', 'order_details.order_id')
				->join('items', 'order_details.meal_id', '=', 'items.id')
                ->get();
            
        return $res;
	}  

	
}