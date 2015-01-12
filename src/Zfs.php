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

    public function createFilesystem($name)
    {
        $process = $this->processBuilder
            ->setArguments(['zfs', 'create', $name])
            ->getProcess()
        ;
        $process->mustRun();

        return true;
    }
}
