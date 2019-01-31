<?php
namespace DalPraS\OAuth2\Client\Helper;

class DateUtcHelper {

    /**
     * @var \DateTimeZone
     */
    private $utcTimeZone;

    /**
     * @param \DateTime $dateTime
     */
    public function __construct() {
        $this->utcTimeZone = new \DateTimeZone('UTC');
    }

    /**
     * Convert DateTime in UTC timezone string format Y-m-d\TH:i:s\Z
     *
     * @param \DateTime $dateTime Local datetime
     * @return string
     */
    public function date2utc(\DateTime $dateTime) : string {
        return $dateTime->setTimezone($this->utcTimeZone)->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * Convert string UTC datetime in current datetime
     */
    public function utc2date(string $utcTime) : \DateTime {
        return (new \DateTime())->createFromFormat('Y-m-d\TH:i:s\Z', $utcTime);
    }
}

