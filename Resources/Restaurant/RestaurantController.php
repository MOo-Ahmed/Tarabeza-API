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

	public function update($_id)
	{
		$validator = new Validator;

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'name'                => 'required|alpha_spaces',
            "logo_url"            => "required",
            'contact_number'      => 'required|alpha_num|min:11|max:11',
            "address"             => "required",
            "longitude"           => "required|numeric",
            "latitude"            => "required|numeric",
            "reservation_active"  => "required|numeric|min:0|max:1",
            "opening_time"        => "required",
            "closing_time"        => "required"
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }
        
		$restaurantModel = new RestaurantModel();
		$restaurant = $restaurantModel->findById($_id);

		if($restaurant)
		{
			$isUpdated = $restaurantModel->updatea($_id, $input);
			if($isUpdated)
			{
				$this->response->renderOk($this->response::HTTP_OK, 
										  "Restaurant data updated successfully");
			}
			else    
			{
				$this->response->renderFail($this->response::HTTP_BAD_REQUEST, 
											"Invalid update data provided.");
			}
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_BAD_REQUEST, 
										"Can't find the restaurant specified.");
		}
	}
	
}