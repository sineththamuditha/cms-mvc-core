<?php

namespace app\core\exceptions;

class notFoundException extends \Exception
{
    protected string $path = '';

    public function __construct(string $path = '')
    {
        $this->path = $path;
        parent::__construct();
    }


    protected $message = 'Page not found';
    protected $code = 404;

    public function getPath(): string
    {
        return $this->path;
    }

}