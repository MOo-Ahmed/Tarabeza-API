<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class AuthModelTest extends PHPUnit\Framework\TestCase
{
	public $authModel;

	// Called once just like normal constructor
	// You can create database connections here etc
	public static function setUpBeforeClass() : void
    {
		$capsule = new Capsule;

		$capsule->addConnection([
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'graduation_project_database',
			'username'  => 'root',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
		]);

		$capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
		$capsule->bootEloquent();
    }

	// Initialize the test case
	// Called for every defined test
    public function setUp() : void
    {
    	$this->authModel = new \Resources\Auth\AuthModel();    	
    }

    public function testSignUp(){
    	$input = [];
    	$email = 'testing@aa.c';
    	$input["email"] = $email;
    	$input["phone_number"] = "01024970738";
    	$input["password"] = "password";
    	$input["first_name"] = "Mahmoud";
    	$input["last_name"] = "Ahmed";
    	$input["gender"] = "male";
    	$input["date_of_birth"] = "0000-00-00";
    	$input["role"] = "customer";

		$this->authModel->insert($input); 		 
		$data = $this->authModel->findByEmail($email);		
		$this->assertEquals($data["email"], $email);
    }

	public function testFindByExistingEmail() 
	{
		$email = 'aaaaaaaaas@aa.c'; 
		$data = $this->authModel->findByEmail($email);
		
		$this->assertEquals($data["email"], $email); //test login with existing email (return email)
	}

	public function testReturnFirstName() 
	{
		$email = 'aaaaaaaaas@aa.c'; 
		$data = $this->authModel->findByEmail($email);
		
		$this->assertEquals($data["first_name"], "Mahmoud"); //test login with existing email (return first name)
	}

	public function testFindByNotExistingEmail() 
	{
		$email = 'test@aa.c'; 
		$data = $this->authModel->findByEmail($email);
		
		$this->assertEquals($data["first_name"], null); //test login with not existing email
	}
}
