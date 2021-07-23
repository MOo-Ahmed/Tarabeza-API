<?php

namespace Resources\User;

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Container\Container;

class UserModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "users";
	protected $fillable = ['email','is_active'];
	protected $hidden = array("password", "updated_at", "created_at");

	public function customer($_id){
		$res = Capsule::table('customers')->select('customers.*')
				->where('customers.user_id', '=', $_id)
				->get();

		return $res ;
	}

	public function manager($_id)
	{
		$res = Capsule::table('restaurant_managers')->select('restaurant_managers.*')
				->where('restaurant_managers.user_id', '=', $_id)
				->get();

		return $res ;
	}

	public function staff($_id)
	{
		$res = Capsule::table('staff')->select('staff.*')
				->where('staff.user_id', '=', $_id)
				->get();

		return $res ;
	}
	
	public function findReservationsByUserId($_id)
    {
    	$res = Capsule::table('reservations')->select('reservations.*')
		    	->join('customers', 'customers.id', '=', 'reservations.customer_id')
		    	->join('users', 'users.id', '=', 'customers.id')
		    	->where('reservations.customer_id', '=', $_id)
		    	->get();
    	

		
		return $res;
    }

    public function insertReservation($_input)
    {
        $res = Capsule::table('reservations')->insertGetId(array(
        'customer_id'   => $_input['customer_id'],
        'restaurant_id' => $_input['restaurant_id'], 
        'table_id' => $_input['table_id'], 
        'comment' => $_input['comment'], 
        'requested_at'  => $_input['requested_at']));
    	

		
		return $res;
    }

	public function findById($_id)
	{
		$res = UserModel::where("users.id", "=", $_id)->first()->toArray();

		return $res;
	}

	public function activate($_id)
	{
		$columns = ["users.is_active" => 1];
		return UserModel::where("users.id", "=", $_id)->update($columns);
	}

	public function addPreference($cust_id, $cat_id){
		$res = Capsule::table('customers_preferences')->insertGetId(array(
			'customer_id' 	=> $cust_id,
			'category_id' 	=> $cat_id));
		return $res ;
	}

	
    
}