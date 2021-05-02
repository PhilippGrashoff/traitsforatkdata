<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Field;

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

    /**
     * makes german formatted strings from date, time and datetime fields
     */
    public function castDateTimeToGermanString(Field $field, bool $shortenTime = false): string
    {
        //no DateTimeInterFace passed? Just return given value
        if ($field->get() instanceof \DateTimeInterface) {
            //TODO: When ATK Fields are fully refactored, refactor this to $field instanceOf Field\DateTime etc
            if ($field->type === 'datetime') {
                if ($shortenTime) {
                    return $field->get()->format('d.m.Y H:i');
                } else {
                    return $field->get()->format('d.m.Y H:i:s');
                }
            }
            if ($field->type === 'date') {
                return $field->get()->format('d.m.Y');
            }
            if ($field->type === 'time') {
                if ($shortenTime) {
                    return $field->get()->format('H:i');
                } else {
                    return $field->get()->format('H:i:s');
                }
            }
        }

        //no DateTime field? return unchanged value
        return (string)$field->get();
    }
}