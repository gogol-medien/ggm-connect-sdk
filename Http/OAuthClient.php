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

use ggm\Connect\Authentication\AccessToken;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Http\HttpClient;
use ggm\Connect\Interfaces\ConnectorInterface;

/**
 * class OAuthClient
 *
 * @package ggm-connect-sdk
 */
class OAuthClient
{
    /**
     * @const string The URI path to the authorization endpoint
     */
    const AUTHORIZATION_PATH = 'oauth/v2/auth';

    /**
     * @const string The URI path to the token endpoint
     */
    const TOKEN_PATH = 'oauth/v2/token';

    /**
     * @var ConnectorInterface
     */
    private $connector;


    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Generates an authorization URL
     *
     * @param  string $redirectUri The callback URL that should receive the auth result
     * @param  string $state       The CSRF token
     * @return string
     */
    public function getAuthorizationUrl($redirectUri, $state)
    {
        $params = array(
            'client_id' => $this->connector->getClientId(),
            'state' => $state,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri
        );

        return $this->connector->getPortalUrl().'/'.self::AUTHORIZATION_PATH.'?'.http_build_query($params, null, '&');
    }

    /**
     * Request an access token with the supplied code
     *
     * @param  string $code
     * @param  string $redirectUri The redirect URI which was used to generate the login URL
     * @return AccessToken
     * @throws SDKException
     */
    public function getAccessTokenFromCode($code, $redirectUri = '')
    {
        $params = array(
            'code' => $code,
            'redirect_uri' => $redirectUri
        );

        return $this->requestAccessToken($params);
    }

    /**
     * Requests an access token from the oauth endpoint
     *
     * @param  array  $params
     * @return AccessToken
     * @throws SDKException
     * @throws HttpException
     * @throws ResponseException
     */
    protected function requestAccessToken(array $params)
    {
        $params['client_id'] = $this->connector->getClientId();
        $params['client_secret'] = $this->connector->getSecret();

        // Determine the grant_type based on the supplied params
        if (isset($params['code'])) {
            $params['grant_type'] = 'authorization_code';
        } else if (isset($params['refresh_token'])) {
            $params['grant_type'] = 'refresh_token';
        } else {
            $params['grant_type'] = 'client_credentials';
        }

        $url = $this->connector->getPortalUrl().'/'.self::TOKEN_PATH.'?'.http_build_query($params, null, '&');

        $response = HttpClient::dispatch($url);

        if ($response->getHttpCode() === 200) {
            return new AccessToken($response->getBody());
        } else {
            throw new SDKException('Unable to obtain access token');
        }
    }
}
