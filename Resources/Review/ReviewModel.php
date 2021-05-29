<?php

namespace Resources\Review;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class ReviewModel extends \Illuminate\Database\Eloquent\Model
{
  public $table = "reviews";
  protected $fillable = ['customer_name', "restaurant_id", "comment", "rate"];
  protected $hidden = array("created_at", "updated_at");

  

  public function insert($_input)
  {
    return ReviewModel::create($_input);
  }

  public function findById($restaurant_id){
    $res = Capsule::table('reviews')->select('reviews.*')
		    	->where('reviews.restaurant_id', '=', $restaurant_id)
		    	->get();
    	
		return $res;
  }

}