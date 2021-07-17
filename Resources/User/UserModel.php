<?php

namespace Resources\User;

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Container\Container;

class UserModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "users";
	protected $fillable = ['email','is_active'];
	protected $hidden = array("password", "updated_at", "created_at");

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