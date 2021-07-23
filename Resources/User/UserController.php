<?php

namespace Resources\User;
use Rakit\Validation\Validator;

class UserController extends \Core\Controller
{
	public function getCustomer($_id)
	{
		$model = new UserModel();
		$customer = $model->customer($_id);

		if($customer)
		{
			$this->response->renderOk($this->response::HTTP_OK, $customer);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find specified user.");
		}
	} 

	public function getManager($_id)
	{
		$model = new UserModel();
		$manager = $model->manager($_id);

		if($manager)
		{
			$this->response->renderOk($this->response::HTTP_OK, $manager);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find specified manager.");
		}
	} 

	public function getStaff($_id)
	{
		$model = new UserModel();
		$staff = $model->staff($_id);

		if($staff)
		{
			$this->response->renderOk($this->response::HTTP_OK, $staff);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find specified user.");
		}
	}
	
	public function showReservations($_id)
	{
		$model = new UserModel();
		$reservations = $model->findReservationsByUserId($_id);

		$this->response->renderOk($this->response::HTTP_OK, $reservations);
	}

	public function insertPreference()
    {
    	
        $validator = new Validator;
        $model = new UserModel();
        
        $input = $this->request->getInput();
        
        // make it
        $validation = $validator->make($input, [
            'customer_id'      => 'required|numeric',
            'category_id'       => 'required|numeric'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }        
      
        $record = $model->addPreference($input['customer_id'], $input['category_id']);
        if($record)
        {
            $this->response->renderOk($this->response::HTTP_CREATED, $record);
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }

	public function checkConfirmationCode()
	{
		$model = new UserModel();
        $validator = new Validator;
        $input = $this->request->getInput();
        $payload = \Core\Authorizer::getPayload();

        // make it
        $validation = $validator->make($input, [
            'confirmation_code'   => 'required|numeric',
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }

		try
		{
			$user = $model->findById($payload->id);

			if($user && $user["confirmation_code"] == $input["confirmation_code"])
	        {
	        	$model->activate($payload->id);
				$data = array();
				$this->response->renderOk($this->response::HTTP_OK, $data);
	        }
	        else
	        {
	        	$this->response->renderFail($this->response::HTTP_BAD_REQUEST, "That code isn't valid. You can request a new one.");
	        }
		}
		catch(\Exception $e)
		{
			$this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Request a new one.");
		}
	}

	public function sendVerifyEmail()
	{
		$model = new UserModel();
		$payload = \Core\Authorizer::getPayload();

        $input["confirmation_code"] = \Core\Utilities\Helper::generateConfirmationCode();

        sendMail($payload->email, "Confirmation Code", $input["confirmation_code"]);
        $model->updateBy("email", $payload->email, "confirmation_code", $input["confirmation_code"]);

        $this->response->renderOk($this->response::HTTP_OK, array());
	}

	public function addReservation()
	{
        $validator = new Validator;
        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'restaurant_id'   => 'required|numeric',
            'table_id'   => 'required|numeric',
            'requested_at'   => 'required'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }

        $payload = \Core\Authorizer::getPayload();
		$model = new UserModel();
		
		$customer = $model->customer($payload->id);

		if(isset($customer[0]))
		{
			$data = file_get_contents($GLOBALS["app"]["recommendation_systems"]["reservation_prediction_url"] . $payload->id . "/" . $input["restaurant_id"]);
			
			$input["comment"] = $data;
			$input["customer_id"] = $customer[0]->id;

			$isInserted = $model->insertReservation($input);

			if($isInserted)
			{

				$this->response->renderOk($this->response::HTTP_OK, "Successfully added your reservation");
			}
			else
			{

				$this->response->renderOk($this->response::HTTP_OK, "Invalid data provided.");
			}
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find customer for specified ID.");
		}
	}

	public function me()
	{
		$model = new UserModel();
		$payload = \Core\Authorizer::getPayload();
		$user = $model->findById($payload->id);

		if($user)
		{
			// unset password
			unset($user["password"]);

			$this->response->renderOk($this->response::HTTP_OK, $user);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find user for specified ID.");
		}
	}

	
}