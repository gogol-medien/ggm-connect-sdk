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
 * Class Location
 *
 * @package ggm-connect-sdk
 */
class Location extends DataNode
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Initializes a Location object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
