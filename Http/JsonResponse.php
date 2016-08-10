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

use ggm\Connect\Exceptions\ResponseException;

/**
 * Class JsonResponse
 *
 * @package ggm-connect-sdk
 */
class JsonResponse
{
    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $body;

    /**
     * Initializes the response with the raw data of a curl response and
     * curl_getinfo data.
     *
     * @param [type] $response [description]
     * @param [type] $info     [description]
     */
    public function __construct($response, $info)
    {
        // Extract header information
        $this->header = array();

        if (isset($info['http_code']) && $info['http_code']) {
            $this->header['http_code'] = $info['http_code'];
        } else {
            throw new ResponseException('Malformed response header (missing http code)');
        }

        if (isset($info['content_type']) && $info['content_type']) {
            $this->header['content_type'] = $info['content_type'];
        } else {
            throw new ResponseException('Malformed response header (missing content type)');
        }

        // We expext the body to contain JSON data
        if ($this->header['content_type'] === 'application/json') {
            $body = json_decode($response, true);

            // If the body is null or false while the http code is 200,
            // something most likely went wrong
            if ((is_null($body) || $body === false) && $this->header['http_code'] === 200) {
                throw new ResponseException('Malformed response body');
            }

            $this->body = $body;
        } else {
            throw new ResponseException('Invalid content type (expected `application/json`)');
        }
    }

    /**
     * Returns the HTTP Code of the response
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->header['http_code'];
    }

    /**
     * Returns the json decoded body
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }
}
