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
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $errorDescription;

    /**
     * Initializes the response with the raw data of a curl response and
     * curl_getinfo data.
     *
     * @param array $response
     * @param array $info
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

            if ($this->header['http_code'] >= 400) {
                // Something went wrong, populate the error fields
                $this->error = $body['error'];
                $this->errorDescription = $body['error_description'];

            } else if ((is_null($body) || $body === false) && $this->header['http_code'] === 200) {
                // If the body is null or false while the http code is 200,
                // something most likely went wrong
                throw new ResponseException('Malformed response body');

            } else {
                $this->body = $body;
            }
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

    /**
     * Returns the error (populated if the http code is >=400)
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns the error description (populated if the http code is >=400)
     *
     * @return string
     */
    public function getErrorDescription()
    {
        return $this->errorDescription;
    }
}
