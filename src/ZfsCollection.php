<?php

namespace Hashnz;

use Doctrine\Common\Collections\ArrayCollection;

class ZfsCollection extends ArrayCollection
{
    /**
     * Create a collection from command output string
     *
     * @param $string
     * @return int      The number of items created
     */
    public function create($string)
    {
        $lines = explode("\n", trim($string));
        $keys = preg_split('/\s+/', strtolower(array_shift($lines)));
        $count = 0;
        foreach ($lines as $l) {
            $values = preg_split('/\s+/', $l);
            $this->add(new Filesystem(array_combine($keys, $values)));
            $count++;
        }

        return $count;
    }
}
