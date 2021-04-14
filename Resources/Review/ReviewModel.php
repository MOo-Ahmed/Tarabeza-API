<?php

namespace Resources\Review;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class ReviewModel extends \Illuminate\Database\Eloquent\Model
{
  public $table = "reviews";
  protected $fillable = ['customer_id', "restaurant_id", "comment", "rate"];
  protected $hidden = array("created_at");

  

  public function insert($_input)
  {
    return ReviewModel::create($_input);
  }

}