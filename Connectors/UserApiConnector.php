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
    public function usersSearch($searchParams)
    {
        $users = null;

        $uri = '/user/api/users.json';
        $response = $this->dispatchRequest($uri, $searchParams);

        if ($response->getHttpCode() === 200) {
            $users = new UserResultSet($response->getBody());
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

        $uri = '/user/api/users/'.$userId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $user = new User($response->getBody());
        }

        return $user;
    }
}
