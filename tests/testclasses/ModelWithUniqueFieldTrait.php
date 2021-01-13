<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use atk4\data\Model;
use traitsforatkdata\UniqueFieldTrait;

class ModelWithUniqueFieldTrait extends Model
{

    use UniqueFieldTrait;

    public $table = 'sometable';

    protected function init(): void
    {
        parent::init();
        $this->addField('unique_field');
    }
}