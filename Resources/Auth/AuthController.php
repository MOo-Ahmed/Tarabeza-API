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
            "phone_number"          => "required|min:5",
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
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Email already exists.");
        }

        $input["password"] = password_hash($input["password"], PASSWORD_DEFAULT);
        $input["confirmation_code"] = \Core\Utilities\Helper::generateConfirmationCode();

        if($input["role"] == "staff"){
            $user = $authModel->insertStaff($input);
        }
        else{
            $user = $authModel->insert($input);
        }
        //$this->response->renderOk($this->response::HTTP_CREATED, $user);
        
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

    public function updateUserInfo()
    {
        
        $validator = new Validator;
        $authModel = new AuthModel();

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'id'                    => 'required|numeric',
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
        
        $authModel = new AuthModel();
        $user =  $authModel->findByID($input["id"]);
        
        if($user && $input["password"] == $user["password"])
        {
            $x = $authModel->upd($input);
            if($x){
                $this->response->renderOk($this->response::HTTP_OK, "User data updated successfully");
            }

            else    
            {
                $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid update data provided.");
            }
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Can't find the user specified.");
        }
        
        
    }

    public function passwordRecovery()
    {
        $validator = new Validator;
        $authModel = new AuthModel();

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'email' => 'required|email'
        ]);

        // then validate
        $validation->validate();

        if($validation->fails())
        {
            // handling errors
            $errors = $validation->errors();
            $this->response->renderErrors($this->response::HTTP_BAD_REQUEST, $errors->firstOfAll());
        }
        
        $confirmationCode = \Core\Utilities\Helper::generateConfirmationCode();
        $authModel = new AuthModel();
        $isUpdated = $authModel->updateConfirmationCodeByEmail($input["email"], $confirmationCode);
        
        if($isUpdated)
        {
            $subject = "Password Recovery - Confirmation code";
            if(\core\EmailSender::send($input["email"], $subject, $confirmationCode))
            {
                $this->response->renderOk($this->response::HTTP_OK, "Confirmation code sent successfully.");
            }
            else
            {
                $this->response->renderOk($this->response::HTTP_OK, "Your email is invalid.");
            }
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Can't find the user specified.");
        }
    }

    public function checkConfirmationCode()
    {
        $validator = new Validator;
        $authModel = new AuthModel();

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'email' => 'required|email',
            'confirmation_code' => 'required|numeric|min:10000|max:99999',
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
        
        if($user)
        {
            if($user["confirmation_code"] == $input["confirmation_code"])
            {
                $user["new_password"] = password_hash($input["password"], PASSWORD_DEFAULT);
              
                $x = $authModel->updatePassword($user);
                if($x)
                {
                    $this->response->renderOk($this->response::HTTP_OK, "User password updated successfully.");
                }
                else
                {
                    $this->response->renderOk($this->response::HTTP_OK, "User password not updated.");
                }
                
            }
            else
            {
                $this->response->renderOk($this->response::HTTP_OK, "Your confirmation code is invalid.");
            }
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Can't find the user specified.");
        }
    }

    public function updatePassword()
    {
        
        $validator = new Validator;
        $authModel = new AuthModel();

        $input = $this->request->getInput();

        // make it
        $validation = $validator->make($input, [
            'id'                    => 'required|numeric',
            'password'              => 'required|min:8|max:255',
            'new_password'          => 'required|min:8|max:255'
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
        $user =  $authModel->findByID($input["id"]);
        
        if($user && $input["password"] == $user["password"])
        {
            $x = $authModel->updatePassword($input);
            if($x){
                $this->response->renderOk($this->response::HTTP_OK, "User password updated successfully");
            }

            else    
            {
                $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Invalid passowrd provided.");
            }
        }
        else
        {
            $this->response->renderFail($this->response::HTTP_BAD_REQUEST, "Can't find the user specified " . $user["password"]);
        }
        
        
    }
}