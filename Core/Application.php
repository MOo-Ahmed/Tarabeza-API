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
        $router->post("/auth/update_pass", ['controller' => 'Auth\\AuthController', 'action' => 'updatePassword']);
        

        // users
        $router->get("/users/me", ['controller' => 'User\\UserController', 'action' => 'me'])
               ->middleware(["admin", "customer", "restaurant_manager", "staff"]);

       $router->post("/users/send_verify_email", 
                    ['controller' => 'User\\UserController', 'action' => 'sendVerifyEmail'])
                ->middleware(["admin", "customer", "restaurant_manager", "staff"]);
                
        $router->post("/users/check_confirmation_code", 
                    ['controller' => 'User\\UserController', 'action' => 'checkConfirmationCode'])
                ->middleware(["admin", "customer", "restaurant_manager", "staff"]);        
        
        $router->post("/cust/pref/add", ['controller' => 'User\\UserController', 'action' => 'insertPreference']);
        
        $router->get("/customer/([0-9]+)", ['controller' => 'User\\UserController', 'action' => 'getCustomer']);

        $router->post("/reviews/add", ['controller' => 'Review\\ReviewController', 'action' => 'postCustomerReview']);
        $router->get("/reviews/([0-9]+)", ['controller' => 'Review\\ReviewController', 'action' => 'index']);

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
        $router->get("/restaurants/dash/([0-9]+)", ['controller' => 'Order\\OrderController', 'action' => 'dashboard']);
        $router->get("/restaurants/branch/([0-9]+)", ['controller' => 'Restaurant\\RestaurantController', 'action' => 'recommendBranches']);


        $router->put("/restaurants/([0-9]+)", ['controller' => 'Restaurant\\RestaurantController', 'action' => 'update']);
		//------------------------------------------------------------
		$router->get("/menu/([0-9]+)", ['controller' => 'Menu\\MenuController', 'action' => 'index']);
        $router->get("/menu/recommend/([0-9]+)/([0-9]+)", ['controller' => 'Menu\\MenuController', 'action' => 'recommendItems']);
        $router->put("/menu/([0-9]+)", ['controller' => 'Menu\\MenuController', 'action' => 'update']);
        //------------------------------------------------------------
        $router->post("/orders/make", ['controller' => 'Order\\OrderController', 'action' => 'insertOrder']);
        $router->post("/orders/finish", ['controller' => 'Order\\OrderController', 'action' => 'finishOrder']);
        $router->post("/orders/approve", ['controller' => 'Order\\OrderController', 'action' => 'approveOrder']);
        $router->post("/orders/cancel", ['controller' => 'Order\\OrderController', 'action' => 'cancelOrder']);
        $router->get("/orders/rest_not_finished/([0-9]+)", ['controller' => 'Order\\OrderController', 'action' => 'getRestaurantNonFinishedOrders']);
        $router->get("/orders/cust_not_finished/([0-9]+)", ['controller' => 'Order\\OrderController', 'action' => 'getCustomerNonFinishedOrders']);

   
        //$router->get("/orders/customer/([0-9]+)", ['controller' => 'Order\\OrderController', 'action' => 'showCustomer']);
        //$router->get("/orders/restaurant/([0-9]+)", ['controller' => 'Order\\OrderController', 'action' => 'showRestaurant']);

        //------------------------------------------------------------
        $router->get("/tables/([0-9]+)", ['controller' => 'Table\\TableController', 'action' => 'index']);
        $router->get("/tables/release/([0-9]+)/([0-9]+)", ['controller' => 'Table\\TableController', 'action' => 'release']);
        $router->get("/tables/hold/([0-9]+)/([0-9]+)", ['controller' => 'Table\\TableController', 'action' => 'hold']);
        $router->post("/tables/available", ['controller' => 'Table\\TableController', 'action' => 'getAvailable']);

        $router->post("/tables", ['controller' => 'Table\\TableController', 'action' => 'insertTable']);

        $router->get("/customer/reservations/([0-9]+)", ['controller' => 'User\\UserController', 'action' => 'showReservations']);

        // Resolve
        $router->resolve();

    }
}