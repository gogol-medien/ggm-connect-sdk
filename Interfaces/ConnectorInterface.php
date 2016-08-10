<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Interfaces;


interface ConnectorInterface
{
    /**
     * @return string
     */
    public function getClientId();

    /**
     * @return string
     */
    public function getSecret();

    /**
     * @return string
     */
    public function getPortalUrl();

    /**
     * @return array
     */
    public function getScope();
}
