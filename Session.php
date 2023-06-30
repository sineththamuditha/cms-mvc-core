<?php

namespace app\core;

/**
 * @method method(string $string)
 * @method expects(\PHPUnit\Framework\MockObject\Rule\InvokedCount $once)
 */
class Session
{

    // flash messages are messages that are displayed only once
    // array key of flash messages
    private const FLASH_KEY = 'flashMessages';

    // constructor checks if there are any flash messages in the session
    // if there are any, it sets the remove flag to true
    public function __construct()
    {

        // start session if not already started
        if( session_status() === PHP_SESSION_NONE ) session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        // update new values
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    // function to set flash messages
    // initialize the remove flag to false
    public function setFlash($key, $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    // function to get flash messages
    public function getFlash($key): mixed
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? '';
    }

    // destructor to remove flash messages
    // if the remove flag is set to true, remove the flash message
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    // function to set session variables
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    // function to get session variables
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    // function to remove session variables
    public function remove($key): bool
    {
        if(!empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

}