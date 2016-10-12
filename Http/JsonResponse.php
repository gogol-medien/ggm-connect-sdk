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
class JsonResponse extends Response
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


    protected function postConstruct()
    {
        // We expect the body to contain JSON data
        if ($this->header['content_type'] === 'application/json') {

            $body = json_decode($this->body, true);

            if ($this->header['http_code'] >= 400) {
                // Something went wrong, populate the error fields
                $this->error = isset($body['error']) ? $body['error'] : null;
                $this->errorDescription = isset($body['error_description']) ? $body['error_description'] : null;

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
}
