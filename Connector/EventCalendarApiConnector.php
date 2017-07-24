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

use ggm\Connect\DataNode\Eventitem;
use ggm\Connect\DataNode\EventitemResultSet;

/**
 * Class EventCalendarApiConnector
 *
 * @package ggm-connect-sdk
 */
class EventCalendarApiConnector extends BaseConnector
{
    /**
     * Retrieves the Eventitem node for an ID
     *
     * @param  int $eventitemId
     * @return Eventitem
     * @throws SDKException
     */
    public function eventitemGet($eventitemId)
    {
        $eventitem = null;

        $uri = '/event/api/eventitems/'.$eventitemId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $eventitem = new Eventitem($response->getBody());
        }

        return $eventitem;
    }

    /**
     * Posts a new Eventitem
     *
     * @param  Eventitem $eventitem
     * @return array
     * @throws SDKException
     */
    public function eventitemPost(Eventitem $eventitem)
    {
        $uri = '/event/api/eventitems.json';

        $data = ['eventitem' => $this->prepareEventitemForDispatch($eventitem)];

        return $this->dispatchRequest($uri, $data, 'POST')->getBody();
    }

    /**
     * Updates an existing Eventitem
     *
     * @param  Eventitem $eventitem
     * @return bool
     * @throws SDKException
     */
    public function eventitemPut(Eventitem $eventitem)
    {
        $uri = '/event/api/eventitems/'.$eventitem->getId().'.json';

        $data = ['eventitem' => $this->prepareEventitemForDispatch($eventitem)];

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

        $uri = '/event/api/images/'.$imageId.'.json';
        $response = $this->dispatchRequest($uri);

        if ($response->getHttpCode() === 200) {
            $image = new Image($response->getBody());
        }

        return $image;
    }

    /**
     * Posts new Images into the eventitem context
     *
     * @param  array
     * @return array
     * @throws SDKException
     */
    public function imagesPost(array $images)
    {
        $uri = '/event/api/images.json';
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
        $uri = '/event/api/images/'.$image->getId().'.json';

        $data = $this->prepareImageForDispatch($image);

        return $this->dispatchRequest($uri, $data, 'PUT')->getHttpCode() === 204;
    }

    /**
     * Retrieves all Eventitem nodes belonging to a selection
     *
     * @param  int $eventitemSelectionId
     * @return EventitemResultSet
     * @throws SDKException
     */
    public function evenitemSelectionGet($eventitemSelectionId)
    {
        $eventitemList = null;

        $uri = '/event/api/eventitems.json';
        $response = $this->dispatchRequest($uri, [ 'eventitem_selection_id' => $eventitemSelectionId ]);

        if ($response->getHttpCode() === 200) {
            $eventitemList = new EventitemResultSet($response->getBody());
        }

        return $eventitemList;
    }

    /**
     * Converts an Eventitem node into the appropriate JSON
     * representation for HTTP POST/PUT requests
     *
     * @param  Eventitem $eventitem
     * @return string
     */
    protected function prepareEventitemForDispatch(Eventitem $eventitem)
    {
        $retData = [];

        !$eventitem->getId() ?: $retData['id'] = $eventitem->getId();
        !$eventitem->getStatus() ?: $retData['status'] = $eventitem->getStatus();
        !$eventitem->getCreated() ?: $retData['created'] = $eventitem->getCreated()->format(\DateTime::ATOM);

        !is_null($eventitem->getTitle()) ?: $retData['title'] = $eventitem->getTitle();
        !is_null($eventitem->getDescription()) ?: $retData['description'] = $eventitem->getDescription();

        !$eventitem->getCategory() ?: $retData['category'] = ['id' => $eventitem->getCategory()->getId()];
        !$eventitem->getUser() ?: $retData['user'] = ['id' => $eventitem->getUser()->getId()];
        !$eventitem->getLocation() ?: $retData['location'] = ['id' => $eventitem->getLocation()->getId()];

        if (is_array($this->getEventitemDates())) {
            $retData['eventitem_dates'] = array_map(function($item) {
                return (string)$item;
            });
        }

        if (is_array($eventitem->getImages())) {
            if ($eventitem->getId()) {
                // When updating an existing Eventitem, we need to send an array of image ids
                $retData['images'] = array_map(function($image) {
                    return $image->getId();
                }, $eventitem->getImages());
            } else {
                // When creating a new Eventitem, we need to send an array of URL structs
                $retData['upload_images'] = [];
                foreach ($eventitem->getImages() as $image) {
                    if (!$image->getDownloadUrl()) {
                        continue;
                    }

                    !$eventitem->getUser() || $image->getUser() ?: $image->setUser($eventitem->getUser());
                    $retData['upload_images'][] = $this->prepareImageForDispatch($image);
                }
            }
        }

        return json_encode($retData);
    }
}
