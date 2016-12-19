<?php

namespace A5sys\MonitorBundle\Converter;

/**
 */
class DateConverter
{
    /**
     *
     * @param string $rawDate
     * @return Datetime
     */
    public static function convert($rawDate)
    {
        return \Datetime::createFromFormat('Y-m-d H:i:s', $rawDate);
    }
}
