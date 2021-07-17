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
		if(is_numeric($_id) != true){
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
		$model = new RestaurantModel();
		$restaurant["restaurant_data"] = $model->findById($_id);
		if($restaurant)
		{
			$this->response->renderOk($this->response::HTTP_OK, $restaurant);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No restaurant found.");
		}
	}
	
}