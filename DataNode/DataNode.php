<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\DataNode;

/**
 * Class DataNode
 *
 * @package ggm-connect-sdk
 */
abstract class DataNode
{
    /**
     * Creates a stub instance which can be used as
     * reference in put / post requests.
     *
     * @param  mixed $id
     * @return object
     */
    public static function getStubWithId($id)
    {
        return new static(['id' => $id]);
    }
}
