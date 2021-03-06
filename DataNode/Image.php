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
 * Class Image
 *
 * @package ggm-connect-sdk
 */
class Image extends DataNode
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var string
     */
    protected $copyright;

    /**
     * @var array
     */
    protected $urlSet;

    /**
     * @var int
     */    
    protected $remoteId;

    /**
     * @var string
     */
    protected $downloadUrl;

    /**
     * Initializes an Image object with the response
     * data of a request to the corresponding api endpoint
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user = isset($data['user']) ? User::getStubWithId($data['user']['id']) : null;
        $this->url = $data['url'] ?? null;
        $this->caption = $data['caption'] ?? null;
        $this->copyright = $data['copyright'] ?? null;
        $this->urlSet = $data['url_set'] ?? null;
        $this->remoteId = $data['remote_id'] ?? null;        
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRemoteId()
    {
        return $this->remoteId;
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
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     * @return Image
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     * @return Image
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

    /**
     * @return array
     */
    public function getUrlSet()
    {
        return $this->urlSet;
    }

    /**
     * @return string
     */
    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }

    /**
     * @param string $downloadUrl
     * @return Image
     */
    public function setDownloadUrl($downloadUrl)
    {
        $this->downloadUrl = $downloadUrl;
        return $this;
    }
}
