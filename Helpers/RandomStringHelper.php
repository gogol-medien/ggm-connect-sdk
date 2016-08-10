<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Helpers;

use ggm\Connect\Exceptions\SDKException;

class RandomStringHelper
{
    /**
     * @return string
     * @throws SDKException
     */
    public static function getRandomString($length)
    {
        if (function_exists('mcrypt_create_iv')) {
            return self::mcryptRandomString($length);
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return self::opensslRandomString($length);
        }

        throw new SDKException('Unable to generate random string');
    }

    /**
     * @param  string $binary
     * @return string
     */
    private static function bin2Hex($binary, $length)
    {
        return \substr(\bin2hex($binary), 0, $length);
    }

    /**
     * @param  int $length
     * @return string
     */
    private static function mcryptRandomString($length)
    {
        return self::bin2Hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM), $length);
    }

    /**
     * @param  int $length
     * @return string
     */
    private static function opensslRandomString($length)
    {
        return self::bin2Hex(openssl_random_pseudo_bytes($length), $length);
    }
}
