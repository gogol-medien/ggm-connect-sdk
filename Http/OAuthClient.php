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
use ggm\Connect\Connectors\BaseConnector;
use ggm\Connect\Exceptions\AccessTokenExpiredException;
use ggm\Connect\Exceptions\HttpException;
use ggm\Connect\Exceptions\ResponseException;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Http\HttpClient;

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
     * @var BaseConnector
     */
    private $connector;


    public function __construct(BaseConnector $connector)
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
     * Request an AccessToken with the supplied code
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
     * Request an AccessToken with the supplied refresh token
     *
     * @param  string $refreshToken
     * @return AccessToken
     */
    public function getAccessTokenFromRefresh($refreshToken)
    {
        $params = array(
            'refresh_token' => $refreshToken
        );

        return $this->requestAccessToken($params);
    }

    /**
     * Request an AccessToken with the configured client credentials
     *
     * @return AccessToken
     */
    public function getAccessTokenFromClientCredentials()
    {
        return $this->requestAccessToken();
    }

    /**
     * Requests an access token from the oauth endpoint
     *
     * @param  array  $params
     * @return AccessToken
     * @throws AccessTokenExpiredException
     * @throws SDKException
     */
    protected function requestAccessToken(array $params = array())
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

        try {
            $response = HttpClient::dispatchGET($url);
        } catch (ResponseException $ex) {
            throw new SDKException('Invalid server response');
        } catch (HttpException $ex) {
            throw new SDKException('Server connection failed');
        }

        if ($response->getHttpCode() === 200) {
            return new AccessToken($response->getBody());
        } else if ($response->getHttpCode() === 400) {
            if ($response->getError() === 'invalid_grant') {
                throw new AccessTokenExpiredException('The token used for the grant request has expired or is invalid');
            } else if ($response->getError() === 'invalid_client') {
                throw new SDKException('Invalid oauth client configuration');
            } else {
                throw new SDKException('Unable to request an access token (reason: bad request)');
            }
        } else {
            throw new SDKException('Unable to request an access token (reason: server error)');
        }
    }
}
