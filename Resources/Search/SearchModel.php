<?php

namespace Resources\Search;

class SearchModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "restaurants";
	protected $hidden = array("updated_at", "created_at");
	
    public function searchBy($_input)
    {
    	$orWhereQueryArr = [];
		for($i = 0; $i < count($_input["categories"]); $i++)
		{
			$element = [];
			$element[] = "categories.name";
			$element[] = "=";
			$element[] = $_input["categories"][$i];
			
			$orWhereQueryArr[] = $element;
		}
		
    	$res = SearchModel::select("restaurants.*")
			->join("restaurant_has_categories", "restaurant_has_categories.restaurant_id", "=", "restaurants.id")
            ->join("categories", "categories.id", "=", "restaurant_has_categories.category_id")
            ->where("restaurants.name", "LIKE", "%".$_input["restaurant_name"]."%")
            ->where(function($query) use ($orWhereQueryArr)
            {
            	$query->where(array_slice($orWhereQueryArr,0,1));
                $query->orWhere(array_slice($orWhereQueryArr,1));
            })
            ->distinct()->limit(15)->get();

		
		return $res;
    }
}