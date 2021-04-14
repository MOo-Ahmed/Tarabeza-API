<?php

namespace Resources\User;
use Rakit\Validation\Validator;

class UserController extends \Core\Controller
{
	public function index()
	{
		/*
		$model = new UserModel();
		$users = $model->findAll();

		if($users)
		{
			foreach ($users as &$user)
			{
				// unset password
			    unset($user["password"]);
			}

			$this->response->renderOk($this->response::HTTP_OK, $users);
		}
		else
		{
			$this->response->renderFail($this->response::HTTP_NOT_FOUND, "Could not find users.");
		}
		*/
	}

	// public function recovery()
	// {
	// 	$model = new UserModel();

	// 	$validator = new \Core\Validation\Validator();
	// 	$input = $this->request->getInput();

 //        $rules = [
 //            'email' => 'required'
 //        ];

 //        if(!$validator->isValid($input, $rules))
 //        {
 //            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, $validator->getError());
 //        }

 //        $user = $model->findByEmail($input["email"]);

 //        if($user)
 //        {
 //        	$model->updateBy("email", $user["email"], "is_active", 1);
 //        	$data = array("Thanks! Please check " . $input["email"] . " for a link to reset your password.");
 //        	$this->response->renderOk($this->response::HTTP_OK, $data);
 //        }
 //        else
 //        {
 //        	$this->response->renderFail($this->response::HTTP_BAD_REQUEST, "No users found.");
 //        }
	// }

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