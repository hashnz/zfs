<?php

namespace spec\Hashnz;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\ProcessBuilder;

class ZfsSpec extends ObjectBehavior
{
    function let()
    {
        $builder = new ProcessBuilder();
        $this->beConstructedWith($builder);

        $builder->setArguments(['sudo', 'zpool', 'destroy', 'testpool'])->getProcess()->run();
        $builder->setArguments(['sudo', 'rm', '-rf', '/tmp/testdisk'])->getProcess()->run();
        $builder->setArguments(['sudo', 'fallocate', '-l', '100M', '/tmp/testdisk'])->getProcess()->mustRun();
        $builder->setArguments(['sudo', 'zpool', 'create', 'testpool', '/tmp/testdisk'])->getProcess()->mustRun();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hashnz\Zfs');
    }

    function it_should_create_a_filesystem()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
    }

    function it_should_return_a_filesystem_collection()
    {
        $this->getFilesystems()->shouldReturnAnInstanceOf('Hashnz\ZfsCollection');
    }
}
