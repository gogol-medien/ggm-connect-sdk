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
 * Class Article
 *
 * @package ggm-connect-sdk
 */
class Article extends DataNode
{
    // Article Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELETED = 'deleted';

    // Static Tag constants
    const STATIC_TAG_COMMERCIAL = 'commercial';
    const STATIC_TAG_PREMIUM_CONTENT = 'premium_content';

    // TextElement constants
    const TE_SUBLINE = 'subline';
    const TE_KICKER = 'kicker';
    const TE_TEXT = 'text';
    const TE_TEASER = 'teaser';


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
     * Initializes an Article object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;

        if (isset($data['status']) && in_array($data['status'], [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_REJECTED, self::STATUS_DELETED])) {
            $this->status = $data['status'];
        }

        if (isset($data['created'])) {
            $this->created = date_create($data['created']) ?: null;
        }

        if (isset($data['updated'])) {
            $this->updated = date_create($data['updated']) ?: null;
        }

        if (isset($data['static_tags']) && in_array($data['static_tags'], [self::STATIC_TAG_COMMERCIAL, self::STATIC_TAG_PREMIUM_CONTENT])) {
            $this->staticTags = $data['static_tags'];
        }

        $this->template = isset($data['template']) ? $data['template'] : null;
        $this->title = isset($data['title']) ? $data['title'] : null;

        $this->textElements = isset($data['text_elements']) ? $data['text_elements'] : [];
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
     * @param string $status
     * @return Article
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Article
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return int
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param int $template
     * @return Article
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getStaticTags()
    {
        return $this->staticTags;
    }

    /**
     * @param array $staticTags
     * @return Article
     */
    public function setStaticTags($staticTags)
    {
        $this->staticTags = $staticTags;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextElement($textElement)
    {
        return isset($this->textElements[$textElement]) ? $this->textElements[$textElement] : null;
    }

    /**
     * @return array
     */
    public function getTextElements()
    {
        return $this->textElements;
    }

    /**
     * @param array $textElements
     * @return Article
     */
    public function setTextElements($textElements)
    {
        $this->textElements = $textElements;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Article
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return ArticleCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param ArticleCategory $category
     * @return Article
     */
    public function setCategory(ArticleCategory $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Article
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     * @return Article
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
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

    /**
     * @param array $tags
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
}
