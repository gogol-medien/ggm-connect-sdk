<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Authentication;

/**
 * class AccessToken
 *
 * @package ggm-connect-sdk
 */
class AccessToken
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * @var int
     */
    protected $expiresAt;

    /**
     * @var array
     */
    protected $scope;

    /**
     *  Initializes the access token properties if passed
     *  a JsonResponse body or similarly structured array
     *
     * @param array|null $responseBody
     */
    public function __construct(array $responseBody = null)
    {
        if (is_array($responseBody)) {
            $this->token = isset($responseBody['access_token']) ? $responseBody['access_token'] : null;
            $this->refreshToken = isset($responseBody['refresh_token']) ? $responseBody['refresh_token'] : null;
            $this->expiresAt = isset($responseBody['expires_in']) ? (time() + (int)$responseBody['expires_in']) : null;
            $this->scope = isset($responseBody['scope']) ? explode(' ', $responseBody['expires_in']) : null;
        }
    }

    public function __toString()
    {
        return $this->token ?: '';
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return int|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return array|null
     */
    public function getScope()
    {
        return $this->scope;
    }
}
