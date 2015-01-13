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

    function letgo()
    {
        $builder = new ProcessBuilder();
        $builder->setArguments(['sudo', 'zpool', 'destroy', 'testpool'])->getProcess()->run();
        $builder->setArguments(['sudo', 'rm', '-rf', '/tmp/testdisk'])->getProcess()->run();
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

    function it_should_return_a_single_filesystem_by_name()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
        $this->getFilesystem('testpool/test')->shouldHaveType('Hashnz\Filesystem');
        $this->getFilesystem('testpool/test')->getName()->shouldBe('testpool/test');
    }

    function it_should_destroy_a_filesystem_by_name()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
        $this->destroyFilesystem('testpool/test')->shouldBe(true);
        $this->shouldThrow('Symfony\Component\Process\Exception\ProcessFailedException')->during('getFilesystem', ['testpool/test']);
    }

    function it_should_snapshot_a_filesystem()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
        $this->createSnapshot('testpool/test')->shouldBe(true);
    }

    function it_should_get_a_snapshot_collection()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
        $this->createSnapshot('testpool/test', 'one')->shouldBe(true);
        $this->createSnapshot('testpool/test', 'two')->shouldBe(true);
        $this->getSnapshots('testpool/test')->shouldHaveType('Hashnz\ZfsCollection');
    }

    function it_should_clone_snapshot()
    {
        $this->createFilesystem('testpool/test')->shouldBe(true);
        $this->createSnapshot('testpool/test', 'one')->shouldBe(true);
        $this->createClone('testpool/test@one', 'testpool/testclone')->shouldBe(true);
    }
}
