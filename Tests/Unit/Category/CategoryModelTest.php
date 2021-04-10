<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

/**
 * 
 */
class CategoryModelTest extends PHPUnit\Framework\TestCase
{
	public $categoryModel;
	
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

    public function setUp() : void
    {
    	$this->categoryModel = new \Resources\Category\CategoryModel();
    }

    public function testCategory(){ 
    	$categories = $this->categoryModel->findAll("0");
    	$isFound = FALSE;
    	if($categories != null){
    		$isFound = TRUE;
    	}
    	$this->assertTrue($isFound); 
    }
    
}