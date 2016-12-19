<?php

namespace A5sys\MonitorBundle\Reader;

use A5sys\MonitorBundle\Converter\DataTypeConverter;
use A5sys\MonitorBundle\Converter\DateConverter;
use A5sys\MonitorBundle\Converter\TimestampConverter;
use A5sys\MonitorBundle\Entity\Request;
use A5sys\MonitorBundle\Exception\DataTypeException;
use A5sys\MonitorBundle\Exception\MonitorException;

/**
 */
class RequestUpdater
{
    const DATE_POSITION = 0;
    const TYPE_POSITION = 1;
    const ID_POSITION = 2;
    const DATA_TYPE_POSITION = 3;
    const VALUE_POSITION = 4;

    /**
     *
     * @param type $columns
     * @return string
     */
    public function getId($columns)
    {
        return $columns[static::ID_POSITION];
    }

    /**
     *
     * @param type $columns
     * @return Request
     * @throws MonitorException
     */
    public function create($columns)
    {
        $request = new Request();

        $logType = $columns[static::TYPE_POSITION];
        if ($logType !== 'request') {
            throw new MonitorException('The columns are not for a request converter');
        }

        $request->setId($columns[static::ID_POSITION]);
        $request->setDate(DateConverter::convert($columns[static::DATE_POSITION]));

        return $request;
    }

    /**
     *
     * @param Request $request
     * @param array   $columns
     * @throws DataTypeException
     */
    public function update(Request $request, $columns)
    {
        $dataType = DataTypeConverter::convert($columns);
        switch ($dataType) {
            case DataTypeConverter::STOP_TYPE:
                $request->setStop(TimestampConverter::convert($columns[static::VALUE_POSITION]));
                break;
            case DataTypeConverter::DURATION_TYPE:
                $request->setDuration($columns[static::VALUE_POSITION]);
                break;
            case DataTypeConverter::MEMORY_TYPE:
                $request->setMemory($columns[static::VALUE_POSITION]);
                break;
            case DataTypeConverter::START_TYPE:
                $request->setStart(TimestampConverter::convert($columns[static::VALUE_POSITION]));
                break;
            case DataTypeConverter::URL_TYPE:
                $request->setUrl($columns[static::VALUE_POSITION]);
                break;
            case DataTypeConverter::USER_TYPE:
                $request->setUser($columns[static::VALUE_POSITION]);
                break;
            default:
                throw new DataTypeException('The type '.$dataType.' is not handled');
        }
    }
}
