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

use ggm\Connect\Exception\HttpException;
use ggm\Connect\Exception\ResponseException;
use ggm\Connect\Http\JsonResponse;
use ggm\Connect\Http\Response;

class HttpClient
{
    /**
     * Dispatches a GET request and returns a response object
     *
     * @param  string $url
     * @return Response
     * @throws HttpException
     */
    public static function dispatchGET($url)
    {
        $ch = curl_init($url);

        $params = array(
			CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        );

        curl_setopt_array($ch, $params);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($response === false) {
            throw new HttpException('Request dispatch failed');
        } else if ($info['http_code'] === 403) {
            throw new HttpException('Access denied');
        }

        switch ($info['content_type']) {
            case 'application/json':
                return new JsonResponse($response, $info);

            default:
                return new Response($response, $info);
        }
    }

    /**
     * Dispatches a POST request and returns a response object.
     *
     * @param  string $url
     * @param  array $postParams
     * @return Response
     */
    public static function dispatchPOST($url, array $postParams)
    {
        $ch = curl_init($url);

        $params = array(
			CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postParams
        );

        curl_setopt_array($ch, $params);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($response === false) {
            throw new HttpException('Request dispatch failed');
        } else if ($info['http_code'] === 403) {
            throw new HttpException('Access denied');
        }

        switch ($info['content_type']) {
            case 'application/json':
                return new JsonResponse($response, $info);

            default:
                return new Response($response, $info);
        }
    }

    /**
     * Dispatches a PUT request and returns a response object.
     *
     * @param  string $url
     * @param  array $putParams
     * @return Response
     */
    public static function dispatchPUT($url, array $putParams)
    {
        $ch = curl_init($url);

        $params = array(
			CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($putParams, null, '&')
        );

        curl_setopt_array($ch, $params);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($response === false) {
            throw new HttpException('Request dispatch failed');
        } else if ($info['http_code'] === 403) {
            throw new HttpException('Access denied');
        }

        switch ($info['content_type']) {
            case 'application/json':
                return new JsonResponse($response, $info);

            default:
                return new Response($response, $info);
        }
    }
}
