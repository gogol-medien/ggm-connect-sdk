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
use ggm\Connect\DataNodes\ArticleCategoryResultSet;
use ggm\Connect\DataNodes\Image;
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

    /**
     * Posts a new Article
     *
     * @param  Article $article [description]
     * @return array
     * @throws SDKException
     */
    public function articlePost(Article $article)
    {
        $uri = '/a/api/articles.json';

        $data = array('article' => $this->prepareArticleForDispatch($article));

        return $this->dispatchRequest($uri, $data, 'POST')->getBody();
    }

    /**
     * Updates an existing Article
     *
     * @param  Article $article [description]
     * @return bool
     * @throws SDKException
     */
    public function articlePut(Article $article)
    {
        $uri = '/a/api/articles.json';

        $data = array('article' => $this->prepareArticleForDispatch($article));

        return $this->dispatchRequest($uri, $data, 'PUT')->getHttpCode() === 204;
    }

    /**
     * Posts new Images into the article context
     *
     * @param  array
     * @return array
     * @throws SDKException
     */
    public function imagesPost(array $images)
    {
        $uri = '/a/api/images.json';

        $data = array('images' => array_map(function($image) {
            return $this->prepareImageForDispatch($image);
        }, $images));

        $data['images'] = json_encode($data['images']);

        return $this->dispatchRequest($uri, $data, 'POST')->getBody();
    }

    /**
     * Updates the meta data (caption, copyright, etc)
     * of an existing Image
     *
     * @param  Image  $image
     * @return bool
     * @throws SDKException
     */
    public function imagePut(Image $image)
    {
        $uri = '/a/api/images/'.$image->getId().'.json';

        $data = $this->prepareImageForDispatch($image);

        return $this->dispatchRequest($uri, $data, 'PUT')->getHttpCode() === 204;
    }

    /**
     * Retrieves the ArticleCategory node for an ID
     *
     * @param  int $categoryId
     * @return ArticleCategory
     */
    public function categoryGet($categoryId)
    {
        $category = null;

        $uri = '/a/api/categories/'.$categoryId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $category = new ArticleCategory($response->getBody());
        }

        return $category;
    }

    /**
     * Retrieves all categories
     *
     * @return ArticleCategoryResultSet
     */
    public function categoriesGet()
    {
        $categories = null;

        $uri = '/a/api/categories.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $categories = new ArticleCategoryResultSet($response->getBody());
        }

        return $categories;
    }

    /**
     * Converts an Article node into the appropriate JSON
     * representation for HTTP POST/PUT requests
     *
     * @param  Article $article
     * @return string
     */
    protected function prepareArticleForDispatch(Article $article)
    {
        $retData = array();

        !$article->getId() ?: $retData['id'] = $article->getId();
        !$article->getStatus() ?: $retData['status'] = $article->getStatus();
        !$article->getSegment() ?: $retData['segment'] = $article->getSegment();
        !$article->getTemplate() ?: $retData['template'] = $article->getTemplate();
        !$article->getCreated() ?: $retData['created'] = $article->getCreated()->format(\DateTime::ISO8601);
        !is_null($article->getTitle()) ?: $retData['title'] = $article->getTitle();
        !is_null($article->getStaticTags()) ?: $retData['static_tags'] = $article->getStaticTags();
        !$article->getCategory() ?: $retData['category'] = array('id' => $article->getCategory()->getId());
        !$article->getUser() ?: $retData['user'] = array('id' => $article->getUser()->getId());
        !$article->getLocation() ?: $retData['location'] = array('id' => $article->getLocation()->getId());
        !is_null($article->getTags()) ?: $retData['tags'] = $article->getTags();

        !$article->getTitle() ?: $retData['title'] = $article->getTitle();
        !$article->getTextElements() ?: $retData['text_elements'] = $article->getTextElements();

        if (is_array($article->getImages())) {
            if ($article->getId()) {
                // When updating an existing Article, we need to send an array of image ids
                $retData['images'] = array_map(function($image) {
                    return $image->getId();
                }, $article->getImages());
            } else {
                // When creating a new article, we need to send an array of URL structs
                $retData['upload_images'] = array();
                foreach ($article->getImages() as $image) {
                    if (!$image->getDownloadUrl()) {
                        continue;
                    }

                    !$article->getUser() || $image->getUser() ?: $image->setUser($article->getUser());
                    $retData['upload_images'][] = $this->prepareImageForDispatch($image);
                }
            }
        }

        return json_encode($retData);
    }

    /**
     * Converts an Image node into the appropriate
     * array structore used for HTTP POST/PUT requests
     *
     * @param  Image  $image
     * @return array
     */
    protected function prepareImageForDispatch(Image $image)
    {
        $retData = array();

        !$image->getId() ?: $retData['id'] = $image->getId();
        !$image->getUser() ?: $retData['user'] = $image->getUser()->getId();
        !$image->getCaption() ?: $retData['caption'] = $image->getCaption();
        !$image->getCopyright() ?: $retData['copyright'] = $image->getCopyright();
        !$image->getDownloadUrl() ?: $retData['url'] = $image->getDownloadUrl();

        return $retData;
    }
}
