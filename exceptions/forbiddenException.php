<?php

namespace app\core\exceptions;

class forbiddenException extends \Exception
{
    protected $message = 'You do not have access to this page';
    protected $code = 403;
}