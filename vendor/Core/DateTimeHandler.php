<?php

namespace Core;

use DateTime;
use DateInterval;
use DateTimeZone;

class DateTimeHandler
{

    protected $datetime;

    /**
     * @param string|int|null $time
     * 
     * @throws Exception
     */
    public function __construct($time = null, DateTimeZone $timezone = null)
    {
        try
        {
            if (is_numeric($time))
            {
                $this->datetime = new \DateTime('@', $time);
                if ($timezone)
                {
                    $this->datetime->setTimezone($timezone);
                }
            }
            else
            {
                $this->datetime = new DateTime($time ?? "now", $timezone);
            }
        }
        catch (Exception $e)
        {
            throw new \Exception("Не верный формат date/time");
        }
    }

    public function format(string $format): string
    {
        return $this->datetime->format($format);
    }

    public function add(DateInterval $interval): self
    {
        $this->datetime->add($interval);
        return $this;
    }

    public function sub(DateInterval $interval): self
    {
        $this->datetime->sub($interval);
        return $this;
    }

    public function diff(DateTimeHelper $other): DateInterval
    {
        return $this->datetime->diff($other->datetime);
    }

    public function isAfter(DateTimeHelper $other): bool
    {
        return $this->datetime > $other->datetime;
    }

    public function addDays(int $days): self
    {
        $this->datetime->add(new DateInterval("P{$days}D"));
        return $this;
    }

    public function addHours(int $hours): self
    {
        $this->datetime->add(new DateInterval("PT{$hours}H"));
        return $this;
    }

    public function addMinutes(int $minutes): self
    {
        $this->datetime->add(new DateInterval("PT{$minutes}M"));
        return $this;
    }

    public function subDays(int $days): self
    {
        $this->datetime->sub(new DateInterval("P{$days}D"));
        return $this;
    }

    public function subHours(int $hours): self
    {
        $this->datetime->sub(new DateInterval("PT{$hours}H"));
        return $this;
    }

    public function subMinutes(int $minutes): self
    {
        $this->datetime->sub(new DateInterval("PT{$minutes}M"));
        return $this;
    }

    public static function now(): self
    {
        return new self();
    }

    public static function fromString(string $dateString): self
    {
        return new self($dateString);
    }

    public static function fromTimestamp(int $timestamp): self
    {
        return new self($timestamp);
    }

    public static function isValid(string $dateString, string $format = 'Y-m-d H:i:s'): bool
    {
        $date = DateTime::createFromFormat($format, $dateString);
        return $date && $date->format($format) === $dateString;
    }

    public function getTimestamp(): int
    {
        return $this->datetime->getTimestamp();
    }

    public function getDateTime(): DateTime
    {
        return clone $this->datetime;
    }
}
