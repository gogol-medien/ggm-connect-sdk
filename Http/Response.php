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
 * Class Response
 *
 * @package ggm-connect-sdk
 */
class Response
{
    /**
     * @var array
     */
    protected $header;

    /**
     * @var mixed
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
        $this->header = [];

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

        $this->body = $response;

        // Post-processing
        $this->postConstruct();
    }

    /**
     * This function should perform any tasks on the header and body
     * data which are needed in derrived classes, i.e. parse the body
     * or perform sanity checks.
     */
    protected function postConstruct()
    {
        // Nothing to do here
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
