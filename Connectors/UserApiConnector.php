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

use ggm\Connect\DataNodes\User;
use ggm\Connect\DataNodes\UserResultSet;
use ggm\Connect\Exceptions\HtttpException;
use ggm\Connect\Exceptions\ResponseException;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Http\HttpClient;

/**
 * Class UserApiConnector
 *
 * @package ggm-connect-sdk
 */
class UserApiConnector extends BaseConnector
{
    /**
     * Search for users based on the supplied params
     *
     * @param  array $searchParams
     * @return UserResultSet
     */
    public function userSearch($searchParams)
    {
        $users = null;

        try {
            $params = array(
                'access_token' => (string)$this->getClientCredentialsAccessToken()
            );

            $url = $this->getPortalUrl().'/user/api/users.json?'.http_build_query($params, null, '&');

            $response = HttpClient::dispatch($url);

            if ($response->getHttpCode() === 200) {
                $users = new UserResultSet($response->getBody());
            }
        } catch (SDKException $ex) {
            // Bubble SDKExceptions
            throw $ex;
        } catch (HtttpException $ex) {
            throw new SDKException('HTTP error: '.$ex->getMessage());
        } catch (ResponseException $ex) {
            throw new SDKException('Response error: '.$ex->getMessage());
        }

        return $users;
    }

    /**
     * Retrieves the User node for an ID
     *
     * @param  int $userId
     * @return User
     * @throws SDKException
     */
    public function userGet($userId)
    {
        $user = null;

        try {
            $params = array(
                'access_token' => (string)$this->getClientCredentialsAccessToken()
            );

            $url = $this->getPortalUrl().'/user/api/users/'.$userId.'.json?'.http_build_query($params, null, '&');

            $response = HttpClient::dispatch($url);

            if ($response->getHttpCode() === 200) {
                $user = new User($response->getBody());
            }
        } catch (SDKException $ex) {
            // Bubble SDKExceptions
            throw $ex;
        } catch (HtttpException $ex) {
            throw new SDKException('HTTP error: '.$ex->getMessage());
        } catch (ResponseException $ex) {
            throw new SDKException('Response error: '.$ex->getMessage());
        }

        return $user;
    }
}
