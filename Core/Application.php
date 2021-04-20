<?php
/**
 *  @file    app.php
 *  @date    05/10/2020
 *  The file initializes the scripts necessary for bootstrap.
 */
namespace Core;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Container\Container;

class Application 
{
    // Application constructor
    private function __construct()
    {}

    /**
     * init
     *
     * @return void
     */
    private static function init()
    {
        if($GLOBALS["app"]["debug"])
        {
            // debug mode
            error_reporting(E_ALL);
            ini_set('log_errors', 1);
            ini_set('error_log', $GLOBALS["storage_paths"]["error_logs"]);
        }
        else
        {
            error_reporting(0);
        }
    }
    
    /**
     * Run the application
     *
     * @return void
     */
    public static function run() 
    {
        self::init();

        // $Capsule = new Capsule;
        // $Capsule->addConnection(config::get('database'));
        // $Capsule->setAsGlobal();  //this is important
        // $Capsule->bootEloquent();
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'nofipayn_restaurant_reservations_system',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        
        ]);


$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
// $users = Capsule::table('users')->get();
//print_r($users);

        $request = new \Core\HTTP\Request();
        $response = new \Core\HTTP\Response();
        $router = new \Core\Router("/api", $request, $response);

        // Set Prefix e.g. /v1
        $router->setPrefix('/' . $GLOBALS["app"]["version"]);

        // ------------------------------------------------------------
        
        // auth
        $router->post("/auth/me", ['controller' => 'Auth\\AuthController', 'action' => 'authMe']);
        $router->post("/auth/register", ['controller' => 'Auth\\AuthController', 'action' => 'register']);
        $router->post("/auth/update_me", ['controller' => 'Auth\\AuthController', 'action' => 'updateUserInfo']);

        // users
        $router->get("/users/me", ['controller' => 'User\\UserController', 'action' => 'me'])
               ->middleware(["admin", "customer", "restaurant_manager", "staff"]);

       $router->post("/users/send_verify_email", 
                    ['controller' => 'User\\UserController', 'action' => 'sendVerifyEmail'])
                ->middleware(["admin", "customer", "restaurant_manager", "staff"]);
                
        $router->post("/users/check_confirmation_code", 
                    ['controller' => 'User\\UserController', 'action' => 'checkConfirmationCode'])
                ->middleware(["admin", "customer", "restaurant_manager", "staff"]);        
        
        $router->post("/reviews/add", ['controller' => 'Review\\ReviewController', 'action' => 'postCustomerReview']);
        
        // ------------------------------------------------------------
        $router->get("/search", ['controller' => 'Search\\SearchController', 'action' => 'index']);

        // ------------------------------------------------------------
        $router->get("/items/categories", ['controller' => 'Category\\CategoryController', 'action' => 'showItemCategories']);
        $router->get("/restaurants/categories", ['controller' => 'Category\\CategoryController', 'action' => 'showRestaurantCategories']);

        // ------------------------------------------------------------
        $router->get("/feeds/([0-9]+)", 
                ['controller' => 'Feed\\FeedController', 'action' => 'show']);
        $router->get("/feeds", ['controller' => 'Feed\\FeedController', 'action' => 'index']);

        // ------------------------------------------------------------
        $router->get("/restaurants", ['controller' => 'Restaurant\\RestaurantController', 'action' => 'index']);
        $router->get("/restaurants/([0-9]+)", ['controller' => 'Restaurant\\RestaurantController', 'action' => 'show']);
        

		//------------------------------------------------------------
		$router->get("/menu/([0-9]+)", ['controller' => 'Menu\\MenuController', 'action' => 'index']);
        $router->get("/menu/recommend", ['controller' => 'Menu\\MenuController', 'action' => 'recommendItems']);
        
        //------------------------------------------------------------
        $router->post("/orders/make", ['controller' => 'Order\\OrderController', 'action' => 'makeOrder']);
        $router->post("/orders/finish", ['controller' => 'Order\\OrderController', 'action' => 'finishOrder']);
        $router->post("/orders/paid", ['controller' => 'Order\\OrderController', 'action' => 'confirmPayOrder']);

        // Resolve
        $router->resolve();

    }
}