<?php

namespace Hashnz;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class Zfs
{
    /**
     * @var ProcessBuilder
     */
    private $processBuilder;

    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * Create a ZFS filesystem
     *
     * @param $name
     * @return bool
     */
    public function createFilesystem($name)
    {
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'create', $name])
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }

    /**
     * Get a collection of filesystems
     *
     * @return ZfsCollection
     */
    public function getFilesystems()
    {
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'list', '-o', 'name,used,avail,refer,mountpoint,origin'])
            ->getProcess()
        ;
        $process->mustRun();
        $output = $process->getOutput();

        $collection = new ZfsCollection();
        foreach ($this->parseFilesystemString($output) as $fs) {
            $collection->add(new Filesystem($fs));
        }

        return $collection;
    }

    /**
     * Get a single filesystem by name
     *
     * @param $name
     * @return Filesystem
     */
    public function getFilesystem($name)
    {
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'list', '-o', 'name,used,avail,refer,mountpoint,origin', $name])
            ->getProcess()
        ;
        $process->mustRun();
        $output = $process->getOutput();

        return new Filesystem(
            $this->parseFilesystemString($output)[0]
        );

        return $coll;
    }

    /**
     * Parse output from a list command into arrays
     *
     * @param $string Output from zfs list
     * @return array  List of filesystems in array format
     */
    private function parseFilesystemString($string)
    {
        $lines = explode("\n", trim($string));
        $keys = preg_split('/\s+/', strtolower(array_shift($lines)));
        $fs = [];
        foreach ($lines as $l) {
            $values = preg_split('/\s+/', $l);
            $fs[] = array_combine($keys, $values);
        }

        return $fs;
    }

    /**
     * Destroy a zfs filesystem
     *
     * @param $name
     * @return bool
     */
    public function destroyFilesystem($name)
    {
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'destroy', $name])
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }

    public function createSnapshot($name, $snap = null)
    {
        if (empty($snap)) {
            $snap = time();
        }
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'snapshot', $name.'@'.$snap])
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }
}
