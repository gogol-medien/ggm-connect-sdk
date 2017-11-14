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
    const TIME_SEPARATOR = 'T';
    const DATE_END_SEPARATOR = 'E';

    const FORMAT_DATE = 'Y-m-d';
    const FORMAT_DATE_TIME = 'Y-m-d\TH:i:s';

    /**
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var boolean
     */
    private $dateHasTime = false;

    /**
     * @param \DateTime $dateStart
     * @return \ggm\Connect\Model\DateTime
     */
    public function setDateStart(\DateTime $dateStart)
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateEnd
     * @return \ggm\Connect\Model\DateTime
     */
    public function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param bool $dateHasTime
     * @return \ggm\Connect\Model\DateTime
     */
    public function setDateHasTime($dateHasTime)
    {
        $this->dateHasTime = $dateHasTime;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDateHasTime()
    {
        return $this->dateHasTime;
    }

    /**
     * Creates a new DateTime instance from a formatted string
     * as it is returned from API requests.
     *
     * Format: '{YYYY}-{MM}-{DD}[T{HH}:{MM}:{SS}[E{YYYY}-{MM}-{DD}T{HH}:{MM}:{SS}]]'
     *
     * Valid possibilities for string:
     * 1. date start without time:      2017-11-10
     * 2. date start with time:         2017-11-10T09:30:00
     * 3. date start with date end:     2017-11-10T09:30:00E2017-11-10T12:45:00
     *
     * @param  string $string
     * @return DateTime
     */
    public static function fromString(string $string)
    {
        $matches = [];

        if (preg_match('/^(\d{4}-\d{2}-\d{2})(?:'.self::TIME_SEPARATOR.'(\d{2}:\d{2}:\d{2})(?:'.self::DATE_END_SEPARATOR.'(\d{4}-\d{2}-\d{2})'.self::TIME_SEPARATOR.'(\d{2}:\d{2}:\d{2})){0,1}){0,1}$/', $string, $matches)) {

            $invalid = false;

            // create DateTime
            $dateTime = new static();

            // Fill DateTime with data
            switch(count($matches)) {
                // dateStart
                case 2:
                    $dateTime->setDateStart(date_create($matches[1]));
                    $dateTime->setDateHasTime(false);
                    break;
                // dateStart, dateStartTime
                case 3:
                    $dateTime->setDateStart(date_create($matches[1].self::TIME_SEPARATOR.$matches[2]));
                    $dateTime->setDateHasTime(true);
                    break;
                // dateStart, dateStartTime, dateEnd, dateEndTime
                case 5:
                    $dateTime->setDateStart(date_create($matches[1].self::TIME_SEPARATOR.$matches[2]));
                    $dateTime->setDateEnd(date_create($matches[3].self::TIME_SEPARATOR.$matches[4]));
                    $dateTime->setDateHasTime(true);
                    break;
                default:
                    $invalid = true;
            }

            // dateStart before dateEnd
            if (!$invalid && $dateTime->getDateEnd() instanceof \DateTime && $dateTime->getDateStart() >= $dateTime->getDateEnd()) {
                $invalid = true;
            }

            return $invalid ? null : $dateTime;

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
        $string = '';

        // if dateStart is set
        if ($this->getDateStart() instanceof \DateTime) {
            // format dateStart
            $string .= $this->getDateHasTime() ? $this->getDateStart()->format(DateTime::FORMAT_DATE_TIME) : $this->getDateStart()->format(DateTime::FORMAT_DATE);

            // if dateEnd is set
            if ($this->getDateEnd() instanceof \DateTime) {
                // format dateEnd
                $string .= self::DATE_END_SEPARATOR.$this->getDateEnd()->format(DateTime::FORMAT_DATE_TIME);
            }
        }

        return $string;
    }
}
