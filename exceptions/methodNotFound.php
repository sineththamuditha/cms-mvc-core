<?php

namespace app\core\exceptions;

class methodNotFound extends \Exception
{
    protected $message = 'Method not found';
    protected $code = 404;
}
