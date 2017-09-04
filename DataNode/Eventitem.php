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

use ggm\Connect\Model\{DateTime, PoiData};


/**
 * Class Eventitem
 *
 * @package ggm-connect-sdk
 */
class Eventitem extends DataNode
{
    // Eventitem Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELETED = 'deleted';

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
     * @var \DateTime
     */
    protected $published;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var array
     */
    protected $staticTags;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var EventitemCategory
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
    protected $eventitemDates = [];

    /**
     * @var PoiData
     */
    protected $poiData;


    /**
     * Initializes an Eventitem object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;

        if (isset($data['status']) && in_array($data['status'], [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_REJECTED, self::STATUS_DELETED])) {
            $this->status = $data['status'];
        }

        if (isset($data['created'])) {
            $this->created = date_create($data['created']) ?: null;
        }

        if (isset($data['updated'])) {
            $this->updated = date_create($data['updated']) ?: null;
        }

        if (isset($data['published'])) {
            $this->published = date_create($data['published']) ?: null;
        }

        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->address = $data['address'] ?? null;

        $this->staticTags = $data['static_tags'] ?? null;
        $this->user = isset($data['user']) ? new User($data['user']) : null;
        $this->category = isset($data['category']) ? new EventCalendarCategory($data['category']) : null;
        $this->location = isset($data['location']) ? new Location($data['location']) : null;
        $this->images = isset($data['images']) ? array_map(function($item) { return new Image($item); }, $data['images']) : null;
        $this->imageCount = isset($data['image_count']) ? $data['image_count'] : null;

        $this->poiData = isset($data['poi_data']) && is_array($data['poi_data']) ? PoiData::fromArray($data['poi_data']) : null;

        if (isset($data['eventitem_dates']) && is_array($data['eventitem_dates'])) {
            foreach ($data['eventitem_dates'] as $dtString) {
                $dt = DateTime::initWithString($dtString);

                if ($dt) {
                    $this->eventitemDates[] = $dt;
                }
            }
        }
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
     * @return Eventitem
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
     * @return Eventitem
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Eventitem
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Eventitem
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Eventitem
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
     * @return Eventitem
     */
    public function setStaticTags($staticTags)
    {
        $this->staticTags = $staticTags;
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
     * @return Eventitem
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return EventCalendarCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param EventCalendarCategory $category
     * @return Eventitem
     */
    public function setCategory(EventCalendarCategory $category = null)
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
     * @return Eventitem
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
     * @return Eventitem
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
     * @return PoiData
     */
    public function getPoiData()
    {
        return $this->poiData;
    }

    /**
     * @param PoiData $poiData
     * @return Eventitem
     */
    public function setPoiData(PoiData $poiData = null)
    {
        $this->poiData = $poiData;
        return $this;
    }

    /**
     * @return array
     */
    public function getEventitemDates()
    {
        return $this->eventitemDates;
    }

    /**
     * @param array $eventitemDates
     * @return Eventitem
     */
    public function setEventitemDates(array $eventitemDates)
    {
        $this->eventitemDates = $eventitemDates;
        return $this;
    }
}

