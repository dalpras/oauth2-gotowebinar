<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

class DateUtcHelper 
{
    /**
     * Convert DateTime in UTC timezone string format Y-m-d\TH:i:s\Z
     */
    public static function date2utc(DateTime|DateTimeImmutable $dateTime): string 
    {
        return $dateTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * Convert string UTC datetime in current datetime
     */
    public static function utc2date(string $utcTime): DateTime 
    {
        return (new DateTime())->createFromFormat('Y-m-d\TH:i:s\Z', $utcTime);
    }
}

