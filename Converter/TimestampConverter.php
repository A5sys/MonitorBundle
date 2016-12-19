<?php

namespace A5sys\MonitorBundle\Converter;

/**
 */
class TimestampConverter
{
    /**
     *
     * @param int $timestamp
     * @return Datetime
     */
    public static function convert($timestamp)
    {
        return \Datetime::createFromFormat('U', $timestamp);
    }
}
