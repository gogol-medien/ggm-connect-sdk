<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Connector;

use ggm\Connect\DataNode\Article;
use ggm\Connect\DataNode\ArticleCategory;
use ggm\Connect\DataNode\ArticleCategoryResultSet;
use ggm\Connect\DataNode\Image;
use ggm\Connect\Exception\SDKException;


/**
 * Class ArticleApiConnector
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
     * @throws SDKException
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
     * @param  Article $article
     * @return array
     * @throws SDKException
     */
    public function articlePost(Article $article)
    {
        $uri = '/a/api/articles.json';

        $data = ['article' => $this->prepareArticleForDispatch($article)];

        return $this->dispatchRequest($uri, $data, 'POST')->getBody();
    }

    /**
     * Updates an existing Article
     *
     * @param  Article $article
     * @return bool
     * @throws SDKException
     */
    public function articlePut(Article $article)
    {
        $uri = '/a/api/articles/'.$article->getId().'.json';

        $data = ['article' => $this->prepareArticleForDispatch($article)];

        return $this->dispatchRequest($uri, $data, 'PUT')->getHttpCode() === 204;
    }

    /**
     * Retrieves one image by ID
     *
     * @return Image
     * @throws SDKException
     */
    public function imageGet($imageId)
    {
        $image = null;

        $uri = '/a/api/images/'.$imageId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $image = new Image($response->getBody());
        }

        return $image;
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
        $data = ['images'];
        foreach ($images as $image) {
            $data['images'][] = $this->prepareImageForDispatch($image);
        }

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
     * @throws SDKException
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
     * @throws SDKException
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
        $retData = [];

        !$article->getId() ?: $retData['id'] = $article->getId();
        !$article->getStatus() ?: $retData['status'] = $article->getStatus();
        !$article->getSegment() ?: $retData['segment'] = $article->getSegment();
        !$article->getTemplate() ?: $retData['template'] = $article->getTemplate();
        !$article->getCreated() ?: $retData['created'] = $article->getCreated()->format(\DateTime::ATOM);
        is_null($article->getTitle()) ?: $retData['title'] = $article->getTitle();
        is_null($article->getStaticTags()) ?: $retData['static_tags'] = $article->getStaticTags();
        !$article->getCategory() ?: $retData['category'] = ['id' => $article->getCategory()->getId()];
        !$article->getUser() ?: $retData['user'] = ['id' => $article->getUser()->getId()];
        !$article->getLocation() ?: $retData['location'] = ['id' => $article->getLocation()->getId()];
        is_null($article->getTags()) ?: $retData['tags'] = $article->getTags();

        !$article->getTextElements() ?: $retData['text_elements'] = $article->getTextElements();

        if (is_array($article->getImages())) {
            if ($article->getId()) {
                // When updating an existing Article, we need to send an array of image ids
                $retData['images'] = array_map(function($image) {
                    return $image->getId();
                }, $article->getImages());
            } else {
                // When creating a new article, we need to send an array of URL structs
                $retData['upload_images'] = [];
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
}
