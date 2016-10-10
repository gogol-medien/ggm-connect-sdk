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

use ggm\Connect\DataNodes\Article;
use ggm\Connect\DataNodes\ArticleCategory;
use ggm\Connect\Exceptions\HtttpException;
use ggm\Connect\Exceptions\ResponseException;
use ggm\Connect\Exceptions\SDKException;


/**
 * Class UserApiConnector
 *
 * @package ggm-connect-sdk
 */
class ArticleApiConnector extends BaseConnector
{
    /**
     * Retrieves the Article node for an ID
     *
     * @param  int $articleId
     * @return Article
     */
    public function articleGet($articleId)
    {
        $article = null;

        $uri = '/a/api/articles/'.$articleId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $article = new Article($response->getBody());
        }

        return $article;
    }

}
