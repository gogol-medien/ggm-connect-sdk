<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Connector;

use ggm\Connect\Authentication\AccessToken;
use ggm\Connect\DataNode\UserInfo;
use ggm\Connect\Exception\AccessTokenExpiredException;
use ggm\Connect\Exception\SDKException;
use ggm\Connect\Helper\RedirectLoginHelper;
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
        $params = [
            'access_token' => (string)$accessToken
        ];

        $url = $this->getPortalUrl().'/user/api/me.json?'.http_build_query($params, null, '&');

        $response = HttpClient::dispatchGET($url);

        return new UserInfo($response->getBody());
    }
}
