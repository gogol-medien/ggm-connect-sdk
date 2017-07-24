<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Helper;


class PersistenceHelper
{
    /**
     * @const string SESSION_PREFIX  The key prefix used when storing data into the session
     */
    const SESSION_PREFIX = 'GGMCSDK_';

    /**
     * @var string
     */
    protected static $persistenceHandler;

    /**
     * @var array
     */
    protected static $data = array();


    public static function get($key)
    {
        return self::{self::getHandler().'get'}($key);
    }

    public static function set($key, $value)
    {
        return self::{self::getHandler().'set'}($key, $value);
    }

    public static function delete($key)
    {
        return self::{self::getHandler().'delete'}($key);
    }

    public static function clear()
    {
        return self::{self::getHandler().'clear'}();
    }

    /**
     * @return string
     */
    private static function getHandler()
    {
        if (!self::$persistenceHandler) {
            if (session_status() === PHP_SESSION_ACTIVE) {
                self::$persistenceHandler = 'session_';
            } else {
                self::$persistenceHandler = 'memory_';
            }
        }

        return self::$persistenceHandler;
    }

    /**
     * Memory Persistence Handler
     */

    /**
     * @param  string $key
     * @return string
     */
    private static function memory_get($key)
    {
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return bool
     */
    private static function memory_set($key, $value)
    {
        self::$data[$key] = $value;
        return true;
    }

    /**
     * @param  string $key
     * @return bool
     */
    private static function memory_delete($key)
    {
        unset(self::$data[$key]);
        return true;
    }

    /**
     * @return bool
     */
    private static function memory_clear()
    {
        self::$data = [];
        return true;
    }


    /**
     * Session Persistence Handler
     */

    /**
     * @param  string $key
     * @return string
     */
    private static function session_get($key)
    {
        return isset($_SESSION[self::SESSION_PREFIX.$key]) ? $_SESSION[self::SESSION_PREFIX.$key] : null;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return bool
     */
    private static function session_set($key, $value)
    {
        $_SESSION[self::SESSION_PREFIX.$key] = $value;
        return true;
    }

    /**
     * @param  string $key
     * @return bool
     */
    private static function session_delete($key)
    {
        unset($_SESSION[self::SESSION_PREFIX.$key]);
        return true;
    }

    /**
     * @return bool
     */
    private static function session_clear()
    {
        // To prevent wiping all session data, make sure to only delete entries
        // when a session key prefix is set
        if (!is_string(self::SESSION_PREFIX) || strlen(self::SESSION_PREFIX) == 0) {
            return false;
        }

        foreach ($_SESSION as $key => $value) {
            if (strpos($key, self::SESSION_PREFIX) === 0) {
                unset($_SESSION[$key]);
            }
        }

        return true;
    }

}
