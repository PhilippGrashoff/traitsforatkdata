<?php declare(strict_types=1);

namespace traitsforatkdata;

trait DateTimeHelpersTrait
{

    /**
     * returns the difference of 2 datetime objects in minutes
     */
    public function getDateDiffTotalMinutes(\DateTimeInterFace $s, \DateTimeInterFace $e): int
    {
        $diff = $s->diff($e);
        return (int)$diff->days * 24 * 60 + $diff->h * 60 + $diff->i;
    }
}