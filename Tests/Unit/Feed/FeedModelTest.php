<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

/**
 * 
 */
class FeedModelTest extends PHPUnit\Framework\TestCase
{
	public $feedModel;
	
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
    	$this->feedModel = new \Resources\Feed\FeedModel();
    }

    public function testRecentRestaurants(){
    	$data = $this->feedModel->recentRestaurants("4");
    	$isFound = FALSE;
    	if($data != null){
    		$isFound = TRUE;
    	}
    	$this->assertTrue($isFound); 
    }
}