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
 * Class Article
 *
 * @package ggm-connect-sdk
 */
class Article
{
    // Article Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELETED = 'deleted';

    // Article Segment constants
    const SEGMENT_ARTICLE = 'article';
    const SEGMENT_IMAGE_POST = 'image_post';
    const SEGMENT_IMAGE_GALLERY = 'image_gallery';
    const SEGMENT_LOTTERY = 'lottery';

    // TextElement constants
    const SUBLINE = 'subline';
    const KICKER = 'kicker';
    const TEXT = 'text';
    const TEASER = 'teaser';


    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var int
     */
    protected $template;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $segment;

    /**
     * @var array
     */
    protected $staticTags;

    /**
     * @var array
     */
    protected $textElements;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var ArticleCategory
     */
    protected $category;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Image
     */
    protected $images;

    /**
     * @var int
     */
    protected $imageCount;

    /**
     * @var array
     */
    protected $tags;


    /**
     * Initializes a User object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->id = isset($data['id']) ? $data['id'] : null;

        if (isset($data['status']) && in_array($data['status'], array(self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_REJECTED, self::STATUS_DELETED))) {
            $this->status = $data['status'];
        }

        if (isset($data['created'])) {
            $this->created = date_create($data['created']) ?: null;
        }

        if (isset($data['updated'])) {
            $this->updated = date_create($data['updated']) ?: null;
        }

        $this->template = isset($data['template']) ? $data['template'] : null;
        $this->title = isset($data['title']) ? $data['title'] : null;

        if (isset($data['segment']) && in_array($data['segment'], array(self::SEGMENT_ARTICLE, self::SEGMENT_LOTTERY, self::SEGMENT_IMAGE_POST, self::SEGMENT_IMAGE_GALLERY))) {
            $this->segment = $data['segment'];
        }

        $this->staticTags = isset($data['static_tags']) ? $data['static_tags'] : null;
        $this->textElements = isset($data['text_elements']) ? $data['text_elements'] : array();
        $this->user = isset($data['user']) ? new User($data['user']) : null;
        $this->category = isset($data['category']) ? new ArticleCategory($data['category']) : null;
        $this->location = isset($data['location']) ? new Location($data['location']) : null;
        $this->images = isset($data['images']) ? array_map(function($item) { return new Image($item); }, $data['images']) : null;
        $this->imageCount = isset($data['image_count']) ? $data['image_count'] : null;
        $this->tags = isset($data['tags']) ? array_map(function($item) { return $item['name_norm']; }, $data['tags']) : null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * @return array
     */
    public function getStaticTags()
    {
        return $this->staticTags;
    }

    /**
     * @return string
     */
    public function getTextElement($textElement)
    {
        return isset($this->textElements[$textElement]) ? $this->textElements[$textElement] : null;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return ArticleCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getImageCount()
    {
        return $this->imageCount;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}
