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
use ggm\Connect\Exceptions\HtttpException;
use ggm\Connect\Exceptions\ResponseException;
use ggm\Connect\Exceptions\SDKException;
use ggm\Connect\Http\HttpClient;

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

        try {
            $params = array(
                'access_token' => (string)$this->getClientCredentialsAccessToken()
            );

            $url = $this->getPortalUrl().'/a/api/articles/'.$articleId.'.json?'.http_build_query($params, null, '&');

            $response = HttpClient::dispatch($url);

            if ($response->getHttpCode() === 200) {
                $article = new Article($response->getBody());
            }
        } catch (SDKException $ex) {
            // Bubble SDKExceptions
            throw $ex;
        } catch (HtttpException $ex) {
            throw new SDKException('HTTP error: '.$ex->getMessage());
        } catch (ResponseException $ex) {
            throw new SDKException('Response error: '.$ex->getMessage());
        }

        return $article;
    }
}
