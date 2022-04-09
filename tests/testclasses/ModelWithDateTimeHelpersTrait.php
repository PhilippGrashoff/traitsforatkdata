<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Data\Model;
use traitsforatkdata\DateTimeHelpersTrait;

class ModelWithDateTimeHelpersTrait extends Model
{
    use DateTimeHelpersTrait;

    public $table = 'ModelWithDateTimeHelpersTrait';

    protected function init(): void
    {
        parent::init();
        $this->addField('datetime', ['type' => 'datetime']);
        $this->addField('date', ['type' => 'date']);
        $this->addField('time', ['type' => 'time']);
        $this->addField('some_other_field');
    }
}