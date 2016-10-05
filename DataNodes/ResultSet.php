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
 * Class ResultSet
 *
 * @package ggm-connect-sdk
 */
abstract class ResultSet
{
    /**
     * @var string
     */
    protected static $hydrationClass = '';

    /**
     * @var array
     */
    protected $raw;

    /**
     * @var array
     */
    protected $hydrated;

    /**
     * Initializes an empty ResultSet
     *
     * @param string $hydrationClass
     */
    public function __construct(array $data)
    {
        $this->raw = $data;
    }

    /**
     * Returns the data in raw form
     *
     * @return array
     */
    public function getRawData()
    {
        return $this->raw;
    }

    /**
     * Returns the data as array of DataNodes
     *
     * @return array
     */
    public function getHydratedData()
    {
        if (is_null($this->hydrated)) {
            $this->hydrate();
        }

        return $this->hydrated;
    }

    protected function hydrate()
    {
        $this->hydrated = array_map(function($data) {
            return new static::$hydrationClass($data);
        }, $this->raw);
    }
}
