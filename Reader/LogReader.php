<?php

namespace A5sys\MonitorBundle\Reader;

use A5sys\MonitorBundle\Reader\RequestUpdater;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class LogReader
{
    const LINE_DELIMITER = "\n";
    const COLUMN_DELIMITER = ']|[';

    protected $requestUpdater;

    /**
     *
     * @param RequestUpdater $requestUpdater
     */
    public function __construct(RequestUpdater $requestUpdater)
    {
        $this->requestUpdater = $requestUpdater;
    }

    /**
     *
     * @param File $file
     * @return Request[]
     */
    public function getEntities(File $file)
    {
        $data = [];

        $lines = $this->getLines($file);

        foreach ($lines as $line) {
            if ($line !== '') {
                $columns = $this->getColumns($line);
                $id = $this->requestUpdater->getId($columns);

                if (!array_key_exists($id, $data)) {
                    $request = $this->requestUpdater->create($columns);
                    $this->requestUpdater->update($request, $columns);
                    $data[$id] = $request;
                } else {
                    $request = $data[$id];
                    $this->requestUpdater->update($request, $columns);
                }
            }
        }

        return $data;
    }

    /**
     *
     * @param File $file
     * @return type
     */
    protected function getLines(File $file)
    {
        $content = file_get_contents($file);

        return explode(static::LINE_DELIMITER, $content);
    }

    /**
     *
     * @param string[] $line
     * @return type
     */
    protected function getColumns($line)
    {
        $columns = explode(static::COLUMN_DELIMITER, $line);

        //remove the first [
        $columns[0] = substr($columns[0], 1);

        //remove the last ]
        $lastIndex = count($columns) - 1;
        $columns[$lastIndex] = substr($columns[$lastIndex], 0, -1);

        return $columns;
    }
}
