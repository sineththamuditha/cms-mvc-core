<?php

namespace app\core;

/**
 * @method expects(\PHPUnit\Framework\MockObject\Rule\InvokedCount $once)
 */
class Cookie
{

    private array $cookies = [];

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    public function setCookie($key, $value, $days = 30):bool {
        if(setcookie($key, $value, time() + 60*60*24*$days)) {
            return true;
        }
        return false;
    }

    public function getCookie($key) {
        return $this->cookies[$key] ?? false;
    }

    public function unsetCookie($key): bool {

        if(isset($this->cookies[$key])) {
            $this->setCookie($key, '', -1);
            unset($this->cookies[$key]);
            return true;
        }
        return false;

    }

    public function getAllCookies():array {
        return $this->cookies;
    }

    public function isRememberMeSet():bool {
        return isset($this->cookies['rememberMe']);
    }
}