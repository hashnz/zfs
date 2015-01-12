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

    public function getFilesystems()
    {
        return new ZfsCollection();
    }

    public function createFilesystem($name)
    {
        $process = $this->processBuilder
            ->add('zfs create '.escapeshellarg($name))
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }
}
