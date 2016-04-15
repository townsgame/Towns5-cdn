<?php namespace app;

use Closure;
use Exception;
use ErrorException;

class Application
{
    /**
     * All of the routes waiting to be registered.
     *
     * @var array
     */
    protected $routes = [];
    
    /**
     * Application constructor.
     */
    public function __construct() {
        date_default_timezone_set('Europe/Prague');
        $this->registerErrorHandling();
        ini_set("register_globals","off");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    /**
     * Set the error handling for the application.
     *
     * @return void
     * @throws Exception
     */
    protected function registerErrorHandling()
    {
        error_reporting(-1);

        set_error_handler(function ($level, $message, $file = '', $line = 0) {
            if (error_reporting() & $level) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }
        });

        set_exception_handler(array($this, 'exception_handler'));
    }

    /**
     * Exception Handler for the application
     *
     * @param $exception
     */
    public function exception_handler($exception) {
        print "Exception Caught: ". $exception->getMessage() ."\n";
        echo "<pre>" . var_export($exception, true) . "</pre>";

    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function patch($uri, $action)
    {
        $this->addRoute('PATCH', $uri, $action);

        return $this;
    }

    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);

        return $this;
    }
    
    /**
     * Register a route with the application.
     *
     * @param  string  $uri
     * @param  mixed  $action
     * @return $this
     */
    public function options($uri, $action)
    {
        $this->addRoute('OPTIONS', $uri, $action);

        return $this;
    }

    /**
     * Add a route to the collection.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  mixed  $action
     */
    protected function addRoute($method, $uri, $action)
    {
        $action = $this->parseAction($action);
        $uri = $uri === '/' ? $uri : '/'.trim($uri, '/');
        $this->routes[$method.$uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    /**
     * Parse the action into an array format.
     *
     * @param  mixed  $action
     * @return array
     */
    protected function parseAction($action)
    {
        if (is_string($action)) {
            return ['uses' => $action];
        } elseif (! is_array($action)) {
            return [$action];
        }

        return $action;
    }

    public function run()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestedUri = $_SERVER["REQUEST_URI"];
        $routeExecuted = false;
        foreach($this->routes as $route) {
            if(is_array($route)
                && isset($route['method']) && $route['method'] === $method
                && isset($route['uri']) && $route['uri'] === $requestedUri) {
                foreach($route['action'] as $action) {
                    list($controller, $method) = explode("@", $action);
                    $handler = new $controller();
                    call_user_func(array($handler, $method));
                    $routeExecuted = true;
                }
            }
        }
        if($routeExecuted) {
            return true;
        }
        //throw new Exception("No route Found");
        //http_response_code(400);
    }

}