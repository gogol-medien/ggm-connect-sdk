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
 * Class Image
 *
 * @package ggm-connect-sdk
 */
class Image
{
    /**
     * @var string
     */
    protected $url;

    /**
     * Initializes a Image object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->url = isset($data['url']) ? $data['url'] : null;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
