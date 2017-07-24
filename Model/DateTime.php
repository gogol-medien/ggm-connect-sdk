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
 * Class DateTime
 *
 * @package ggm-connect-sdk
 */
class DateTime
{
    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $time;

    /**
     * @var bool
     */
    protected $hasTime = false;


    /**
     * Date format: 'YYYY-MM-DD'
     *
     * @param string $date
     * @return DateTime
     */
    public function setDate(string $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Time format: 'HH:MM:SS'
     *
     * @param string|null $time
     * @return DateTime
     */
    public function setTime(string $time = null)
    {
        $this->time = $time;

        $this->hasTime = !is_null($time);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Indicates whether the DateTime object has a time set
     *
     * @return bool
     */
    public function getHasTime()
    {
        return $this->hasTime;
    }

    /**
     * Creates a new DateTime instances from a formatted string
     * as it is returned from API requests.
     *
     * Format: '{YYYY}-{MM}-{DD}[T{HH}:{MM}:{SS}]'
     *
     * @param  string $data
     * @return DateTime
     */
    public static function initWithString(string $data)
    {
        $matches = [];

        if (preg_match('/^(\d{4}-\d{2}-\d{2})(?:T(\d{2}:\d{2}:\d{2})){0,1}$/', $data, $matches)) {

            $instance = (new static())->setDate($matches[1]);

            if (count($matches) === 3) {
                $instance->setTime($matches[2]);
            }

            return $instance;

        } else {
            return null;
        }
    }

    /**
     * Converts a DateTime instance into its string representation.
     * Returns an empty string if the instance has not been properly
     * initialized.
     *
     * @return string
     */
    public function __toString()
    {
        $segments = [];

        if ($this->getDate()) {
            $segments[] = $this->getDate();
        }

        if ($this->getHasTime() && $this->getTime()) {
            $segments[] = $this->getTime();
        }

        return count($segments) ? join('T', $segments) : '';
    }
}
