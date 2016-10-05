<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Connectors;

use ggm\Connect\Authentication\AccessToken;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Http\OAuthClient;

/**
 * Class BaseConnector
 *
 * @package ggm-connect-sdk
 */
abstract class BaseConnector
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var OAuthClient
     */
    protected $oAuthClient;

    /**
     * A client_credential AccessToken
     *
     * @var AccessToken
     */
    protected $ccAccessToken;

    /**
     * Instantiates a new connector object
     *
     * @param array $config
     * @throws SDKException
     */
    public function __construct(array $config = array())
    {
        $config = array_merge(
            array(
                'portal_url' => null,
                'client_id' => null,
                'secret' => null,
                'scope' => array()
            ),
            $config
        );

        // Check if all the required config elements are set
        if (!$config['portal_url']) {
            throw new SDKException('Required "portal_url" not supplied in config');
        }

        if (!$config['client_id']) {
            throw new SDKException('Required "client_id" not supplied in config');
        }

        if (!$config['secret']) {
            throw new SDKException('Required "secret" not supplied in config');
        }

        $this->config = $config;
    }

    /**
     * @return OAuthClient
     */
    protected function getOAuthClient()
    {
        if (!$this->oAuthClient) {
            $this->oAuthClient = new OAuthClient($this);
        }

        return $this->oAuthClient;
    }

    /**
     * @return AccessToken
     */
    protected function getClientCredentialsAccessToken()
    {
        if (is_null($this->ccAccessToken) || $this->ccAccessToken->isExpired()) {
            $this->ccAccessToken = $this->getOAuthClient()->getAccessTokenFromClientCredentials();
        }

        return $this->ccAccessToken;
    }

    public function getClientId()
    {
        return $this->config['client_id'];
    }

    public function getSecret()
    {
        return $this->config['secret'];
    }

    public function getPortalUrl()
    {
        return $this->config['portal_url'];
    }

    public function getScope()
    {
        return $this->config['scope'];
    }
}