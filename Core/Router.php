<?php

namespace Core;

class Router
{
    private $request;
    private $response;

    /**
     * @var array Array of all routes (incl. named routes).
     */
    protected $routes = [];

    protected $roles = [];

    protected static $curl;
    protected static $cmethod;
    /**
     * @var string Can be used to ignore leading part of the Request URL (if main file lives in subdirectory of host)
     */
    protected $basePath = '';

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * Create router in one call from config.
     *
     * @param string $_basePath
     * @param array $_routes
     * @throws Exception
     */
    public function __construct($_basePath, $_request, $_response)
    {
        $this->basePath = $_basePath;
        $this->request = $_request;
        $this->response = $_response;
    }

    /**
     * Set the _prefix.
     * @param string $_prefix
     */
    public function setPrefix($_prefix)
    {
        $this->prefix = $_prefix;
    }
    /**
     * Set the base path.
     * Useful if you are running your application from a subdirectory.
     * @param string $_basePath
     */
    public function setBasePath($_basePath)
    {
        $this->basePath = $_basePath;
    }

    /**
     * Retrieves all routes.
     * Useful if you want to process or display routes.
     * @return array All routes.
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add a route
     *
     * @param string $_method One of 5 HTTP Methods (GET|POST|PUT|DELETE)
     * @param string $_route The route regex, custom regex must start with an @. You can use multiple pre-set regex filters, like [i:id]
     * @param mixed $_callback The callback where this route should point to. Can be anything.
     */
    private function add($_method, $_url, $_callback)
    {
        if($this->prefix != '')
            $_url = $this->prefix . $_url;
        
        self::$cmethod = $_method;
        self::$curl = $_url;
        $this->routes[$_method][$_url] = $_callback;
    }

    public function get($_url, $_callback)
    {
        $this->add("get", $_url, $_callback);
        return $this;
    }

    public function post($_url, $_callback)
    {
        $this->add("post", $_url, $_callback);
        return $this;
    }

    public function put($_url, $_callback)
    {
        $this->add("put", $_url, $_callback);
        return $this;
    }

    public function delete($_url, $_callback)
    {
        $this->add("delete", $_url, $_callback);
        return $this;
    }

    public function resolve()
    {
        $urlMatchFlag = false;

        // set Request Url if it isn't passed as parameter
        $requestUrl =  $this->request->getUrl();

        // set Request Method if it isn't passed as a parameter
        $requestMethod = $this->request->getMethod();

        $urls = $this->routes[$requestMethod] ?? false;

        if(!$urls)
        {
            $this->response->renderFail(405);
        }

        foreach($urls as $url => $callback)
        {
            $urll = $url;

            // If the method matches check the path
            // Add basepath to matching string
            if($this->basePath != '' && $this->basePath != '/')
            {
                $url = '(' . $this->basePath . ')' . $url;
            }

            // Add 'find string start' automatically
            // Add 'find string end' automatically
            $url = '^' . $url . '$';
            
            // Check path match
            if(preg_match('#' . $url . '#', $requestUrl, $matches))
            {
                $urlMatchFlag = true;
                
                // Always remove first element. This contains the whole string
                array_shift($matches);

                if($this->basePath != '' && $this->basePath != '/')
                    array_shift($matches);

                
                if(is_array($callback))
                {
                    //print_r($this->roles);
                    // 
                    if(isset($this->roles[$requestMethod][$urll]))
                    {
                        if(! \Core\Authorizer::isAuthorized($this->request, $this->roles[$requestMethod][$urll]))
                        {
                            $this->response->renderFail(401, "Not authorized.");
                        }
                    }

                    $controller = $this->getNamespace() . $callback["controller"];
                    
                    call_user_func_array(array(new $controller, $callback["action"]), $matches);
                }
                else
                {
                    call_user_func_array($callback, $matches);
                }

                // Don't check other routes
                break;
            }

        }

        // No matching route was found
        if(!$urlMatchFlag)
        {
            $this->response->renderFail(404);
        }
    }


    /**
     * Get the namespace for the controller class. 
     *
     * @return string namespace
     */
    public function middleware(array $_roles)
    {
        $this->roles[self::$cmethod][self::$curl] = $_roles;
    }

    /**
     * Get the namespace for the controller class. 
     *
     * @return string namespace
     */
    protected function getNamespace()
    {
        $namespace = "\\Resources\\";
        return $namespace;
    }

}