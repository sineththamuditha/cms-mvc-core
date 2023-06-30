<?php

namespace app\core;

class Request
{
    private static array $REPLACE_START;

    // constructor function to initialize the base url
    public function __construct()
    {
        self::$REPLACE_START = ['/CommuSupport/' => '/'];
    }

    // function to get the base url
    /**
     * @return array|string[]
     */
    public function getBaseURL(): array
    {
        return self::$REPLACE_START;
    }

    //function to get the path of the request
    /**
     * @return string
     */
    public function getPath(): string
    {
        // get the path from the request uri in the super global variable
        $path = strtr($_SERVER['REQUEST_URI'], self::$REPLACE_START);

        // get the position of the question mark in the path, and if it exists, remove it
        $position = strpos($path, '?');
        $path = $position === false ? $path : substr($path, 0, $position);

        // if the path is empty, return the path as '/'
        if($path[-1] === '/') {
            $path = substr($path, 0, -1);
        }

        // if the path is empty, return the path as '/'
        if($path === '') {
            $path = '/';
        }

        return $path;
    }

    //function to get the request method
    /**
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //function to verify whether the method is get or not
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    //function to verify whether the method is post or not
    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    //function to get the body of the request
    /**
     * @return array
     */
    public function getBody(): array
    {

        $body = [];

        // if the method is get, sanitize the input and store it in the body array
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
//                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // if the method is post, sanitize the input and store it in the body array
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
//                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $body[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;

    }

    //function to get the json data from fetch requests
    /**
     * @return string
     */
    public function getUser(): string {
        $url = $this->getPath();
        $url = explode('/', $url);
        return $url[1];
    }

    //function to get the json data from fetch requests
    /**
     * @return mixed
     */
    public function getJsonData() : mixed {

        // get the json data from fetch requests
        return json_decode(file_get_contents('php://input'), true);

    }
}
