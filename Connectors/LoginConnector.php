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
use ggm\Connect\DataNodes\UserInfo;
use ggm\Connect\Exceptions\AccessTokenExpiredException;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Helpers\RedirectLoginHelper;
use ggm\Connect\Http\HttpClient;


/**
 * Class LoginConnector
 *
 * @package ggm-connect-sdk
 */
class LoginConnector extends BaseConnector
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
     * Instantiates a new PortalConnect object
     *
     * @param array $config
     *
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
     * @return RedirectLoginHelper
     */
    public function getRedirectLoginHelper()
    {
        return new RedirectLoginHelper(
            $this->getOAuthClient()
        );
    }

    /**
     * Uses the refresh token of an AccessToken
     * to request a new token from the oauth endpoint.
     *
     * @param  AccessToken $accessToken
     * @return AccessToken
     * @throws SDKException
     */
    public function refreshAccessToken(AccessToken $accessToken)
    {
        if (!$accessToken->getRefreshToken()) {
            throw new SDKException('AccessToken does not contain a refresh token');
        }

        return $this->getOAuthClient()->getAccessTokenFromRefresh($accessToken->getRefreshToken());
    }

    /**
     * Fetches the UserInfo object for the supplied access token.
     *
     * @param  AccessToken $accessToken
     * @return UserInfo
     */
    public function getUserInfo(AccessToken $accessToken)
    {
        $params = array(
            'access_token' => (string)$accessToken
        );

        $url = $this->getPortalUrl().'/user/api/me.json?'.http_build_query($params, null, '&');

        $response = HttpClient::dispatch($url);

        return new UserInfo($response->getBody());
    }
}
