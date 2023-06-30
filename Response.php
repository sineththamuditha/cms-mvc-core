<?php

namespace app\core;

class Response
{
    private $data;

    // static instance of the class to redirect
    public static function staticRedirect(string $URL) : void {
        (new static())->redirect($URL);
    }

    // function to set the status code
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    // function to redirect to a given URL
    public function redirect(string $URL): void
    {
        header("Location: /CommuSupport".$URL);
    }

    // function to set the json encoded content of the response
    public function setJsonData($data): void
    {
        $this->data = json_encode($data);
    }

    // function to set the content of the response
    public function setData($data): void
    {
        $this->data = $data;
    }

    // function to get the content of the response
    public function getData() {
        return $this->data;
    }

    // function to send the content (echo
    public function send(): void
    {
        echo $this->data;
    }


}
