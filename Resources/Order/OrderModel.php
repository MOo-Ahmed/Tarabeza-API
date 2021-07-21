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
								'order_details.total as total',
								'users.first_name AS first_name',
								'users.last_name AS last_name')
				->where('orders.is_finished', '=', 0)
				->where('orders.is_deleted', '=', 0)
				->where($selector, '=', $id)
				->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->join('users', 'users.id', '=', 'customers.user_id') 
				->join('order_details', 'orders.id', '=', 'order_details.order_id')
				->join('items', 'order_details.meal_id', '=', 'items.id')
				   
                ->get();
            
        return $res;
	}  

	public function dashboard($_id){
		$sql = "SELECT SUM(order_details.total) AS revenue, COUNT(orders.id) AS orders
		FROM order_details inner join orders 
		ON orders.id = order_details.order_id 
		where orders.restaurant_id = " . $_id . "
		AND EXTRACT(DAY FROM CURRENT_TIMESTAMP) = EXTRACT(DAY FROM orders.created_at); " ;
		$res = Capsule::select($sql);
		$revenue = json_decode(json_encode($res), true)[0]['revenue'];
		$count = json_decode(json_encode($res), true)[0]['orders'];
		
		$sql = "SELECT date_format(orders.created_at,'%H %p') as hour,
			  COUNT(orders.id) as hourly_orders
		FROM orders
		WHERE orders.restaurant_id = " . $_id . "
		AND EXTRACT(DAY FROM CURRENT_TIMESTAMP) = EXTRACT(DAY FROM orders.created_at)
		GROUP BY date_format(orders.created_at,'%H %p')
		order by hourly_orders desc LIMIT 4;  " ;

		$topHours = Capsule::select($sql);

		$sql = "SELECT DISTINCT order_details.meal_id AS item_id, items.name AS item_name,
		SUM(order_details.quantity) AS count_orders
		FROM order_details inner join orders 
		ON order_details.order_id = orders.id
		inner join items 
		ON items.id = order_details.meal_id
		where orders.restaurant_id = " . $_id . " 
		AND EXTRACT(DAY FROM CURRENT_TIMESTAMP) = EXTRACT(DAY FROM orders.created_at)
		group by item_id 
		order by count_orders desc LIMIT 4;" ;

		$topItems = Capsule::select($sql);

		$stats = array("revenue" => $revenue, "count_orders" => $count,
		"top_items" => $topItems, "top_hours" => $topHours);

		return $stats;
	}	
}