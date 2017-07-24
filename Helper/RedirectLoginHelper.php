<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Helper;

use ggm\Connect\Authentication\AccessToken;
use ggm\Connect\Exception\SDKException;
use ggm\Connect\Helper\PersistenceHelper;
use ggm\Connect\Helper\RandomStringHelper;
use ggm\Connect\Http\OAuthClient;


class RedirectLoginHelper
{
    /**
     * @const int Length of the CSRF token that is used to validate the login link
     */
    const CSRF_TOKEN_LENGTH = 32;

    /**
     * @var OAuthClient
     */
    protected $oAuthClient;

    /**
     * @param OAuthClient $oAuthClient
     */
    public function __construct(OAuthClient $oAuthClient)
    {
        $this->oAuthClient = $oAuthClient;
    }

    public function getLoginUrl($redirectUrl)
    {
        $state = RandomStringHelper::getRandomString(static::CSRF_TOKEN_LENGTH);
        PersistenceHelper::set('state', $state);

        return $this->oAuthClient->getAuthorizationUrl($redirectUrl, $state);
    }

    /**
     * Obtains an access token object from a successfully performed login redirect
     *
     * @param string $redirectUri
     * @return AccessToken
     */
    public function getAccessToken($redirectUri)
    {
        if (!$code = $this->getCode()) {
            return null;
        }

        $this->validateCsrf();

        return $this->oAuthClient->getAccessTokenFromCode($code, $redirectUri);
    }

    /**
     * Compares the stored CSRF token to the one supplied by the request
     *
     * @throws SDKException
     */
    protected function validateCsrf()
    {
        $state = $this->getState();
        if (!$state) {
            throw new SDKException('CSRF validation failed (missing `state` parameter)');
        }

        $knownState = PersistenceHelper::get('state');
        if (!$knownState) {
            throw new SDKException('CSRF validation failed (missing persistent `state` data)');
        }

        // Remove the CSRF token since it's supposed to be single use
        PersistenceHelper::delete('state');

        if (!hash_equals($knownState, $state)) {
            throw new SDKException('CSRF validation failed');
        }
    }

    /**
     * Returns the state request param
     *
     * @return string
     */
    public function getState()
    {
        return $this->getInput('state');
    }

    /**
     * Returns the code request param
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getInput('code');
    }

    /**
     * Returns the value of a GET param
     *
     * @param  string $key
     * @return string
     */
    protected function getInput($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
}
