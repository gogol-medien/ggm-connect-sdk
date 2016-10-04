<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Http;

use ggm\Connect\Exceptions\HttpException;
use ggm\Connect\Exceptions\ResponseException;
use ggm\Connect\Http\JsonResponse;

class HttpClient
{
    /**
     * Dispatches a request and returns a response object
     *
     * @param  string $url
     * @return JsonResponse
     * @throws  HttpException
     */
    public static function dispatch($url)
    {
        $ch = curl_init($url);

        $params = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        );

        curl_setopt_array($ch, $params);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($response === false) {
            throw new HttpException('Request dispatch failed');
        }

        // ToDo: Once there's different response types, delegate
        // creating the correct response object to a factory class
        return new JsonResponse($response, $info);
    }
}
