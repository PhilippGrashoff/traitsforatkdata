<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Model;

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
    public function castDateTimeToGermanString(Model $entity, string $fieldName, bool $shortenTime = false): string
    {
        //no DateTimeInterFace passed? Just return given value
        $fieldValue = $entity->get($fieldName);
        if ($fieldValue instanceof \DateTimeInterface) {
            //TODO: When ATK Fields are fully refactored, refactor this to $field instanceOf Field\DateTime etc
            $fieldType = $entity->getField($fieldName)->type;
            if ($fieldType === 'datetime') {
                if ($shortenTime) {
                    return $fieldValue->format('d.m.Y H:i');
                } else {
                    return $fieldValue->format('d.m.Y H:i:s');
                }
            } elseif ($fieldType === 'date') {
                return $fieldValue->format('d.m.Y');
            } else {
                if ($fieldType === 'time') {
                    if ($shortenTime) {
                        return $fieldValue->format('H:i');
                    } else {
                        return $fieldValue->format('H:i:s');
                    }
                }
            }
        }

        //no DateTime field? return unchanged value
        return (string)$fieldValue;
    }
}