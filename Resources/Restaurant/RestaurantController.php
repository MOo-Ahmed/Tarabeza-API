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
	public function showQrCode($_id)
	{
		header('Content-Type: image/png');

        $data = $GLOBALS["app"]["restaurant_profile_web"] . $_id;
        $options  = array('s' => "qr", "sf" => 16, "p" => 0);

        $generator = new \Core\QRCode($data, $options);
        $image = $generator->render_image();

		imagepng($image);
		imagedestroy($image);
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

	/*
	public function predictReservation($restID, $userID){
		$data = [];
		$json = json_decode(file_get_contents($GLOBALS["app"]["recommendation_systems"]["reservation_prediction_url"] . $userID . "/" . $restID));
		$data = $json->Data;

		if($data)
		{
			$this->response->renderOk($this->response::HTTP_OK, $data);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No reservation data exists.");
		}
	}
	*/

	public function recommendBranches($restID){
		$data = [];
		$json = json_decode(file_get_contents($GLOBALS["app"]["recommendation_systems"]["branch_url"] . $restID));
		$data = $json;

		if($data)
		{
			$this->response->renderOk($this->response::HTTP_OK, $data);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "No reservation data exists.");
		}
	}
}