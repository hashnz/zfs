<?php

namespace spec\Hashnz;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemSpec extends ObjectBehavior
{
    private $data;

    function let()
    {
        $this->data = [
            'name' => 'foo',
            'used' => '1G',
            'avail' => '2G',
            'refer' => '1k',
            'mountpoint' => '/testpool',
            'origin' => '-',
        ];
        $this->beConstructedWith($this->data);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hashnz\Filesystem');
    }

    function it_can_be_constructed_from_an_array()
    {
        $this->getName()->shouldBe($this->data['name']);
        $this->getUsed()->shouldBe($this->data['used']);
        $this->getAvail()->shouldBe($this->data['avail']);
        $this->getRefer()->shouldBe($this->data['refer']);
        $this->getMountpoint()->shouldBe($this->data['mountpoint']);
        $this->getOrigin()->shouldBe($this->data['origin']);
    }
}
