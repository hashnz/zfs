<?php

namespace Hashnz;

class Filesystem
{
    private $name;
    private $used;
    private $avail;
    private $refer;
    private $mountpoint;
    private $origin;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    /**
     * Set Filesystem data
     *
     * @param array $data
     */
    private function setData(array $data)
    {
        $this->name = $data['name'];
        $this->used = $data['used'];
        $this->avail = $data['avail'];
        $this->refer = $data['refer'];
        $this->mountpoint = $data['mountpoint'];
        $this->origin = $data['origin'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @return string
     */
    public function getAvail()
    {
        return $this->avail;
    }

    /**
     * @return string
     */
    public function getRefer()
    {
        return $this->refer;
    }

    /**
     * @return string
     */
    public function getMountpoint()
    {
        return $this->mountpoint;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}
