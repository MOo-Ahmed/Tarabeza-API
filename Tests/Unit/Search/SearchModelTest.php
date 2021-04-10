<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class SearchModelTest extends PHPUnit\Framework\TestCase
{
	public $searchModel;

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
    	$this->searchModel = new \Resources\Search\SearchModel();    	
    }

    public function testSearchBy(){
    	$input = [];
    	$input["restaurant_name"] = "Sheikh El Shawerma";
    	$input["categories"] = ["Oriental","Shawerma"];
    	$data = $this->searchModel->searchBy($input);
    	$this->assertEquals($data[0]["name"], "Sheikh El Shawerma");
    }

    public function testSearchByGetID(){
    	$input = [];
    	$input["restaurant_name"] = "Sheikh El Shawerma";
    	$input["categories"] = ["Oriental","Shawerma"];
    	$data = $this->searchModel->searchBy($input);
    	$this->assertEquals($data[0]["id"], "7");
    }

}