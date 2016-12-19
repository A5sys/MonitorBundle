<?php

namespace A5sys\MonitorBundle\Services;

use A5sys\MonitorBundle\Entity\Request;
use A5sys\MonitorBundle\Reader\LogReader;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class MonitorService
{
    protected $logReader;
    protected $thresholdWarning;
    protected $thresholdError;

    /**
     *
     * @param LogReader $logReader
     * @param int       $thresholdWarning
     * @param int       $thresholdError
     */
    public function __construct(LogReader $logReader, $thresholdWarning, $thresholdError)
    {
        $this->logReader = $logReader;
        $this->thresholdWarning = $thresholdWarning;
        $this->thresholdError = $thresholdError;
    }

    /**
     *
     * @param File $file
     *
     * @return []
     */
    public function compute(File $file)
    {
        $data = [];

        $entities = $this->logReader->getEntities($file);

        $data['withoutEnd'] = $this->getWithoutEnd($entities);
        $data['slowWarning'] = $this->getSlowRequest($entities, $this->thresholdWarning, $this->thresholdError);
        $data['slowError'] = $this->getSlowRequest($entities, $this->thresholdError);

        return $data;
    }

    /**
     *
     * @param array $entities
     * @return type
     */
    protected function getWithoutEnd($entities)
    {
        $data = [];
        /* @var $entity Request */
        foreach ($entities as $entity) {
            if ($entity->getEnd() === null) {
                $data[] = $entity;
            }
        }

        return $data;
    }

    /**
     *
     * @param array $entities
     * @param int   $threshold
     * @return type
     */
    protected function getSlowRequest($entities, $thresholdMin, $thresholdMax = null)
    {
        $data = [];
        /* @var $entity Request */
        foreach ($entities as $entity) {
            $duration = $entity->getDuration();
            if ($duration >= $thresholdMin) {
                if ($thresholdMax) {
                    if ($duration < $thresholdMax) {
                        $data[] = $entity;
                    }
                } else {
                    $data[] = $entity;
                }
            }
        }

        usort($data, array('A5sys\MonitorBundle\Services\MonitorService', 'sortByDuration'));

        return $data;
    }

    /**
     *
     * @param type $a
     * @param type $b
     * @return int
     */
    protected static function sortByDuration($a, $b)
    {
        if ($a->getDuration() === $b->getDuration()) {
            return 0;
        }

        return ($a->getDuration() < $b->getDuration()) ? 1 : -1;
    }
}
