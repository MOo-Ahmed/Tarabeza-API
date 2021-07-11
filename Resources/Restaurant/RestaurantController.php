<?php
namespace Resources\Restaurant;

use Rakit\Validation\Validator;

class RestaurantController extends \Core\Controller
{
	public function index()
	{
		$restaurants = RestaurantModel::get();
		$this->response->renderOk($this->response::HTTP_OK, $restaurants);
	}

	public function show($_id)
	{
		$model = new RestaurantModel();
		$restaurant["restaurant_data"] = $model->findById($_id);
		//$restaurant["items"] = $model->findItemsByRestaurantId($_id);
		if($restaurant)
		{
			$this->response->renderOk($this->response::HTTP_OK, $restaurant);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No restaurant found.");
		}
	}

	public function allTables($_id){
		
	}
	
}