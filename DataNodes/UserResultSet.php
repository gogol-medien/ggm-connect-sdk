<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\DataNodes;

/**
 * Class UserResultSet
 *
 * @package ggm-connect-sdk
 */
class UserResultSet extends ResultSet
{
    /**
     * @var string
     */
    protected static $hydrationClass = 'ggm\Connect\DataNodes\User';
}
