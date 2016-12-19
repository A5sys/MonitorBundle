<?php

namespace A5sys\MonitorBundle\Entity;

/**
 *
 */
class Request
{
    protected $date;
    protected $id;
    protected $user;
    protected $url;
    protected $start;
    protected $stop;
    protected $duration;
    protected $memory;

    /**
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @return Datetime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *
     * @return Datetime
     */
    public function getEnd()
    {
        return $this->stop;
    }

    /**
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     *
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     *
     * @param \Datetime $date
     */
    public function setDate(\Datetime $date)
    {
        $this->date = $date;
    }

    /**
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     *
     * @param Datetime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     *
     * @param Datetime $stop
     */
    public function setStop($stop)
    {
        $this->stop = $stop;
    }

    /**
     *
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     *
     * @param int $memory
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;
    }
}
