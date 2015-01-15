<?php

namespace Hashnz;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;

class Zfs
{
    /**
     * @var array Base command to list zfs filesystems
     */
    private static $listCommand = ['sudo', 'zfs', 'list', '-o', 'name,used,avail,refer,mountpoint,origin'];

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
     * @throws ProcessFailedException
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
     * @throws ProcessFailedException
     */
    public function getFilesystems()
    {
        $process = $this->processBuilder->setArguments(self::$listCommand)->getProcess();
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
     * @throws ProcessFailedException
     */
    public function getFilesystem($name)
    {
        $process = $this->processBuilder
            ->setArguments(self::$listCommand)
            ->add($name)
            ->getProcess()
        ;
        $process->mustRun();
        $output = $process->getOutput();

        return new Filesystem(
            $this->parseFilesystemString($output)[0]
        );
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
     * @param $name string
     * @param $force bool Whether to force destruction
     * @return bool
     * @throws ProcessFailedException
     */
    public function destroyFilesystem($name, $force = false)
    {
        $command = ['sudo', 'zfs', 'destroy', $name];
        if ($force) {
            $command[] = '-r';
        }
        $process = $this->processBuilder
            ->setArguments($command)
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }

    /**
     * Create the snapshot $name@$snap
     *
     * @param string $name
     * @param string $snap Defaults to time()
     * @return bool
     * @throws ProcessFailedException
     */
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

    /**
     * Get a snapshot collection by name
     *
     * @param $name
     * @return ZfsCollection
     * @throws ProcessFailedException
     */
    public function getSnapshots($name)
    {
        $process = $this->processBuilder
            ->setArguments(self::$listCommand)
            ->add('-t')
            ->add('snapshot')
            ->add('-r')
            ->add($name)
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
     * Clone a snapshot
     * @param $name
     * @return bool
     * @throws ProcessFailedException
     */
    public function createClone($snap, $name)
    {
        $process = $this->processBuilder
            ->setArguments(['sudo', 'zfs', 'clone', $snap, $name])
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }
}
