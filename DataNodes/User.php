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
 * Class User
 *
 * @package ggm-connect-sdk
 */
class User extends DataNode
{
    /**
     * User Type Constants
     */
    const TYPE_PERSON = 'person';
    const TYPE_INSTITUTION = 'institution';
    const TYPE_COMPANY = 'company';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var \DateTime
     */
    protected $registrationDate;

    /**
     * @var string
     */
    protected $userType;

    /**
     * @var string
     */
    protected $userSegment;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var array
     */
    protected $staticTags;


    /**
     * Initializes a User object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->firstName = isset($data['first_name']) ? $data['first_name'] : null;
        $this->lastName = isset($data['last_name']) ? $data['last_name'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->enabled =  isset($data['enabled']) ? $data['enabled'] : null;

        if (isset($data['registration_date'])) {
            $this->registrationDate = date_create($data['registration_date']) ?: null;
        }

        if (isset($data['user_type']) && in_array($data['user_type'], array(self::TYPE_PERSON, self::TYPE_COMPANY, self::TYPE_INSTITUTION))) {
            $this->userType = $data['user_type'];
        }

        $this->userSegment = isset($data['user_segment']) ? $data['user_segment'] : null;
        $this->location = isset($data['location']) ? new Location($data['location']) : null;
        $this->staticTags = isset($data['static_tags']) && is_array($data['static_tags']) ? $data['static_tags'] : null;
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return string
     */
    public function getUserSegment()
    {
        return $this->userSegment;
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
    public function getStaticTags()
    {
        return $this->staticTags;
    }
}
