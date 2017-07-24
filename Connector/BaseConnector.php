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
use ggm\Connect\Exception\SDKException;
use ggm\Connect\Http\HttpClient;
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
     * @param array $config
     * @throws SDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge(
            [
                'portal_url' => null,
                'client_id' => null,
                'secret' => null,
                'scope' => []
            ],
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

    /**
     * Dispatches a request using a client_credential access token
     *
     * @param  string $uri
     * @param  array $params
     * @param  string $method
     * @return ggm\Connect\Http\Response
     * @throws SDKException
     */
    protected function dispatchRequest($uri, array $params = [], $method = 'GET')
    {
        $response = null;

        try {
            $accessToken = (string)$this->getClientCredentialsAccessToken();

            switch ($method) {
                case 'GET':
                    $params['access_token'] = $accessToken;
                    $url = $this->getPortalUrl().$uri.'?'.http_build_query($params, null, '&');
                    $response = HttpClient::dispatchGET($url);
                    break;

                case 'POST':
                    $url = $this->getPortalUrl().$uri.'?'.http_build_query(['access_token' => $accessToken], null, '&');
                    $response = HttpClient::dispatchPOST($url, $params);
                    break;

                case 'PUT':
                    $url = $this->getPortalUrl().$uri.'?'.http_build_query(['access_token' => $accessToken], null, '&');
                    $response = HttpClient::dispatchPUT($url, $params);
                    break;

                default:
                    throw new SDKException('Invalid method');
            }

        } catch (SDKException $ex) {
            // Bubble SDKExceptions
            throw $ex;
        } catch (HtttpException $ex) {
            throw new SDKException('HTTP error: '.$ex->getMessage());
        } catch (ResponseException $ex) {
            throw new SDKException('Response error: '.$ex->getMessage());
        }

        return $response;
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

    /**
     * Converts a generic Image node into the appropriate
     * array structure used for HTTP POST/PUT requests
     *
     * @param  Image  $image
     * @return array
     */
    protected function prepareImageForDispatch(Image $image)
    {
        $retData = [];

        !$image->getId() ?: $retData['id'] = $image->getId();
        !$image->getUser() ?: $retData['user'] = $image->getUser()->getId();
        !$image->getCaption() ?: $retData['caption'] = $image->getCaption();
        !$image->getCopyright() ?: $retData['copyright'] = $image->getCopyright();
        !$image->getDownloadUrl() ?: $retData['url'] = $image->getDownloadUrl();

        return $retData;
    }
}
