<?php declare(strict_types=1);

namespace traitsforatkdata;

use atk4\data\Exception;

trait UniqueFieldTrait
{

    public function isFieldUnique(string $fieldName): bool
    {
        if (empty($this->get($fieldName))) {
            throw new Exception(
                'The value for a unique field may not be empty. Field name: ' . $fieldName . ' in ' . __FUNCTION__
            );
        }
        $other = new static($this->persistence);
        //only load field to save performance
        $other->only_fields = [$this->id_field, $fieldName];
        $other->addCondition($this->id_field, '!=', $this->get($this->id_field));
        $other->tryLoadBy($fieldName, $this->get($fieldName));

        return !$other->loaded();
    }
}
