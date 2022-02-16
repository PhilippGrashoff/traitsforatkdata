<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Field;

trait GermanMoneyFormatFieldTrait
{

    protected function germanPriceForMoneyField(Field $field)
    {
        $field->typecast = [
            null,
            function ($value, $field, $persistence) {
                return round((float)str_replace(",", ".", (string)$value), 4);
            },
        ];
    }
}