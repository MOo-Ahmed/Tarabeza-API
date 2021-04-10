<?php
namespace Resources\Feed;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;
class FeedModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "restaurants";
	protected $hidden = array("updated_at", "created_at");

	public function recentRestaurants($_limit)
    {
    	$res = Capsule::table('restaurants')->orderBy('id', 'DESC')->limit($_limit)->get();
		return $res;
    }

    public function recentItems($_limit)
    {
    	$res = Capsule::table('items')->orderBy('id', 'DESC')->limit($_limit)->get();
		return $res;
    }
}