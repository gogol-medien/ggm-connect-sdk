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
     * Initializes the access token properties if passed
     * a JsonResponse body or similarly structured array,
     * or the access token as a string.
     *
     * Alternatively, you can pass an access token string
     * to create a simple access token with reduced functionality.
     *
     * @param array|string|null $tokenData
     */
    public function __construct($tokenData = null)
    {
        if (is_array($tokenData)) {

            $this->token = $tokenData['access_token'] ?? null;
            $this->refreshToken = $tokenData['refresh_token'] ?? null;
            $this->expiresAt = isset($tokenData['expires_in']) ? (time() + (int)$tokenData['expires_in']) : null;
            $this->scope = isset($tokenData['scope']) ? explode(' ', $tokenData['scope']) : null;

        } else if (is_string($tokenData)) {
            $this->token = $tokenData;
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

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return is_numeric($this->expiresAt) ? ($this->expiresAt < time()) : true;
    }
}
