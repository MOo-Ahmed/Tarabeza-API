<?php

namespace Resources\Auth;
use \Firebase\JWT\JWT;
use Rakit\Validation\Validator;

class AuthController extends \Core\Controller
{
    public function authMe()
    {
        $validator = new Validator;
        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'email'                 => 'required|email',
            'password'              => 'required|min:8|max:255'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }


        $authModel = new AuthModel();
        $user = $authModel->findByEmail($input["email"]);

        if($user && password_verify($input["password"], $user["password"]))
        {
            $jwt = \Core\Authorizer::createJWT($user);
            
            $data = array("token" => $jwt);

            $this->response->renderOk($this->response::HTTP_OK, $data);
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid login credentials provided.");
        }
    }

    public function register()
    {
        $validator = new Validator;
        $authModel = new AuthModel();

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'email'                 => 'required|email',
            "phone_number"          => "required|numeric",
            'password'              => 'required|min:8|max:255',
            "first_name"            => "required|min:3|max:255",
            "last_name"             => "required|min:3|max:255",
            "gender"                => "required|in:male,female",
            "date_of_birth"         => "required|date",
            "role"                  => "required|in:customer,staff,restaurant_manager"
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }
        
        $user = $authModel->findByEmail($input["email"]);
        if($user)
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Email is already exists.");
        }

        $input["password"] = password_hash($input["password"], PASSWORD_DEFAULT);
        $input["confirmation_code"] = \Core\Utilities\Helper::generateConfirmationCode();


        $user = $authModel->insert($input);
        if($user)
        {
            //\Core\Utilities\Helper::sendMail($input["email"], "Thanks For Registering", "Code = " . $input["confirmation_code"]);

            $user = $authModel->findByEmail($input["email"]);

            $jwt = \Core\Authorizer::createJWT($user);
            
            $data = array("token" => $jwt);

            $this->response->renderOk($this->response::HTTP_CREATED, $data);
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid data provided.");
        }
    }


}