<?php
namespace DalPraS\OAuth2\Client\Helper;

class DateUtcHelper 
{
    /**
     * Convert DateTime in UTC timezone string format Y-m-d\TH:i:s\Z
     *
     * @param \DateTime $dateTime Local datetime
     * @return string
     */
    public static function date2utc(\DateTimeInterface $dateTime) : string {
        return $dateTime->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * Convert string UTC datetime in current datetime
     * 
     * @param string $utcTime String representing the time
     * @return \DateTime
     */
    public static function utc2date(string $utcTime) : \DateTime {
        return (new \DateTime())->createFromFormat('Y-m-d\TH:i:s\Z', $utcTime);
    }
}

