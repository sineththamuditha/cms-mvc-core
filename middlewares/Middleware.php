<?php

namespace app\core\middlewares;

use app\core\exceptions\forbiddenException;

abstract class Middleware
{
    protected string $GUEST = 'guest';
    protected string $DONEE = 'donee';
    protected string $DONOR = 'donor';
    protected string $DRIVER = 'driver';
    protected string $LOGISTIC = 'logistic';
    protected string $MANAGER = 'manager';
    protected string $CHO = 'cho';
    protected string $ADMIN = 'admin';


    public function execute($func, $userType):void
    {
        if (!in_array($userType, $this->accessRules()[$func],true) || empty($userType)) {
            throw new forbiddenException();
        }

    }
    abstract protected function accessRules(): array;

}