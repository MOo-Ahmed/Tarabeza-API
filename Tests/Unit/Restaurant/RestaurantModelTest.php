<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class RestaurantModelTest extends PHPUnit\Framework\TestCase
{
	public $restaurantModel;

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
    	$this->restaurantModel = new \Resources\Restaurant\RestaurantModel();    	
    }

    public function testSearchRestaurantByID(){
    	$data = $this->restaurantModel->findById("7");
    	$this->assertEquals($data[0]["name"], "Sheikh El Shawerma");
    }

    public function testFindItemsByRestaurantId(){
    	$res = $this->restaurantModel->findItemsByRestaurantId("1");
    	$data = json_decode(json_encode($res), true); 	
    	$this->assertEquals($data[0]["name"], "test1");
    }

}