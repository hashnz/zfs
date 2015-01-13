<?php

namespace spec\Hashnz;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PHPUnit_Framework_Assert as a;

class ZfsCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Hashnz\ZfsCollection');
    }

//    function it_should_covert_text_to_a_collection()
//    {
//        $text = "NAME                 USED  AVAIL  REFER  MOUNTPOINT           ORIGIN
//testpool             110K  63.4M    30K  /testpool            -
//zfs                  514K  2.93G    31K  /zfs                 -
//zfs/mysql            383K  2.93G    31K  /zfs/mysql           -
//zfs/mysql/repslave   352K  2.93G   352K  /zfs/mysql/repslave  -";
//
//        $this->create($text)->shouldReturn(4);
//        $this->first()->shouldHaveType('Hashnz\Filesystem');
//
//    }
}
