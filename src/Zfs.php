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

        $op = $process->getOutput();

        $coll = new ZfsCollection();
        $coll->create($op);

        return $coll;
    }
}
