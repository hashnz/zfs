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
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hashnz\Zfs');
    }

    function it_should_return_a_filesystem_collection()
    {
        $this->getFilesystems()->shouldReturnAnInstanceOf('Hashnz\ZfsCollection');
    }

    function it_should_create_a_filesystem()
    {
        $this->createFilesystem('pool/test')->shouldBe(true);
    }
}
