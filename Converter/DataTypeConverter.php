<?php

namespace A5sys\MonitorBundle\Converter;

use A5sys\MonitorBundle\Reader\RequestUpdater;

/**
 */
class DataTypeConverter
{
    const START_TYPE = 'start';
    const STOP_TYPE = 'stop';
    const DURATION_TYPE = 'duration';
    const MEMORY_TYPE = 'memory';
    const URL_TYPE = 'url';
    const USER_TYPE = 'user';

    /**
     *
     * @param type $columns
     * @return type
     */
    public static function convert($columns)
    {
        return $columns[RequestUpdater::DATA_TYPE_POSITION];
    }

    /**
     *
     * @return type
     */
    public static function getAllTypes()
    {
        return [
            static::START_TYPE,
            static::STOP_TYPE,
            static::DURATION_TYPE,
            static::MEMORY_TYPE,
            static::URL_TYPE,
            static::USER_TYPE,
        ];
    }
}
