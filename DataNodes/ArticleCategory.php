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
 * Class ArticleCategory
 *
 * @package ggm-connect-sdk
 */
class ArticleCategory extends DataNode
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $nameNorm;


    /**
     * Initializes a ArticleCategory object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->nameNorm = isset($data['name_norm']) ? $data['name_norm'] : null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
