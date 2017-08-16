<?php

/*
* This file is part of the ggm-connect-sdk package.
*
* (c) gogol-medien <https://github.com/gogol-medien>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ggm\Connect\Model;

/**
 * Class PoiData
 *
 * @package ggm-connect-sdk
 */
class PoiData
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $zipcode;

    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $lng;

    /**
     * @var string
     */
    protected $url;

    /**
     * Creates a new object instance and populates it
     * with the data of the supplied array
     *
     * @param  array  $data
     * @return PoiData
     */
    public static function fromArray(array $data)
    {
        return (new self())
            ->setId(isset($data['id']) ? (string)$data['id'] : null)
            ->setName(isset($data['name']) ? (string)$data['name'] : null)
            ->setStreet(isset($data['street']) ? (string)$data['street'] : null)
            ->setCity(isset($data['city']) ? (string)$data['city'] : null)
            ->setZipcode(isset($data['zipcode']) ? (string)$data['zipcode'] : null)
            ->setLat(isset($data['lat']) ? (float)$data['lat'] : null)
            ->setLng(isset($data['lng']) ? (float)$data['lng'] : null)
            ->setUrl(isset($data['url']) ? (string)$data['url'] : null)
        ;
    }

    /**
     * Checks if the supplied array can be used to create a valid PoiData object
     *
     * @param  array   $data
     * @return boolean
     */
    public static function isValidArray(array $data)
    {
        if (isset($data['id']) && strlen((string)$data['id']) === 0) {
            return false;
        }

        // name is always required
        if (!isset($data['name']) || strlen((string)$data['name']) === 0) {
            return false;
        }

        if (isset($data['street']) && strlen((string)$data['street'])  === 0) {
            return false;
        }

        if (isset($data['city']) && strlen((string)$data['city']) === 0) {
            return false;
        }

        if (isset($data['zipcode']) && strlen((string)$data['zipcode']) === 0) {
            return false;
        }

        if (isset($data['lat']) && !is_numeric($data['lat'])) {
            return false;
        }

        if (isset($data['lat']) && !is_numeric($data['lng'])) {
            return false;
        }

        if (isset($data['url']) && strlen((string)$data['url']) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];

        foreach (['id', 'name', 'street', 'city', 'zipcode', 'lat', 'lng', 'url'] as $key) {
            $data[$key] = $this->{$key};
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return PoiData
     */
    public function setId(string $id = null)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PoiData
     */
    public function setName(string $name = null)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return PoiData
     */
    public function setStreet(string $street = null)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return PoiData
     */
    public function setCity(string $city = null)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return PoiData
     */
    public function setZipcode(string $zipcode = null)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return PoiData
     */
    public function setLat(float $lat = null)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     * @return PoiData
     */
    public function setLng(float $lng = null)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return PoiData
     */
    public function setUrl(string $url = null)
    {
        $this->url = $url;
        return $this;
    }
}
