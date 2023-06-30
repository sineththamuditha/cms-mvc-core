<?php

namespace app\core;

use app\core\exceptions\methodNotFound;
use app\core\exceptions\notFoundException;
use function Composer\Autoload\includeFile;

class Router
{
    public Request $request;
    public Response $response;
    private array $routes = [];

    //constructor function to initialize the request and response
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    // getter function to get the routes array
    public function getRoutes() : array {
        return $this->routes;
    }

    //function to add the get route to the routes array
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    //function to add the post route to the routes array
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    // checks if the path exists in the routes array
    // if not throws not found exception
    // if yes calls the callback function, and returns the result
    public function resolve()
    {
        // get the path and method from the request
        $path = $this->request->getPath();
        $method = $this->request->method();

        // get the callback function from the routes array
        $callback = $this->routes[$method][$path] ?? false;

        // if the callback is false, throw not found exception (404)
        if ($callback === false) {
            throw new notFoundException($path);
        }

        // if the callback is a string, return the string
        if(is_string($callback)) {
            return $callback;
        }

        if(is_callable($callback)) {
            // if the callback is a function, call the function
            return call_user_func($callback,$this->request,$this->response);
        }

        // if the provided argument is none of them, throw not found exception (404)
        throw new methodNotFound();

    }

    public function render($view, $title,$params = [], $active = ""): string
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layout = $this->layoutContent();
        $navbar = $this->renderNavbar($active);
        $layout = str_replace("{title}", $title, $layout);
        $layout = str_replace("{navbar}", $navbar, $layout);
        return str_replace('{content}', $viewContent, $layout);
    }

    public function renderWithoutNavbar($view, $title, $params = []):string {
        $viewContent = $this->renderOnlyView($view, $params);
        $layout = $this->layoutContent();
        $layout = str_replace("{title}", $title, $layout);
        return str_replace('{navbar}', $viewContent, $layout);
    }

    public function renderOnlyView($view,$params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }

    public function layoutContent(): string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/baseLayout.php";
        return ob_get_clean();
    }

    public function renderNavbar(): string {
        $userType = Application::session()->get('userType');
        if($userType === "guest") {
            return "{content}";
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/navbar/sidenav-$userType.php";
        return ob_get_clean();


    }

    public function sendData($data, $status = 200): void
    {
        $this->response->setStatusCode($status);
        $this->response->setJsonData($data);
        $this->response->send();
    }

}
