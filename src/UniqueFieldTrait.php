<?php declare(strict_types=1);

namespace traitsforatkdata;

use atk4\data\Exception;

trait UniqueFieldTrait
{

    public function isFieldUnique(string $field_name): bool
    {
        if (empty($this->get($field_name))) {
            throw new Exception(
                'The value for a unique field may not be empty. Field name: ' . $field_name . ' in ' . __FUNCTION__
            );
        }
        $other = new static($this->persistence);
        //only load field to save performance
        $other->only_fields = [$this->id_field, $field_name];
        $other->addCondition($this->id_field, '!=', $this->get($this->id_field));
        $other->tryLoadBy($field_name, $this->get($field_name));

        return $other->loaded();
    }
}
