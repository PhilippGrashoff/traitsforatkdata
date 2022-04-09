<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Core\AppScopeTrait;
use Atk4\Data\Model;
use traitsforatkdata\CreatedByTrait;

class ModelWithCreatedByTrait extends Model {

    use CreatedByTrait;

    public $table = 'ModelWithCreatedByTrait';

    protected function init(): void
    {
        parent::init();
        $this->addField('somefield');
        $this->addCreatedByFieldAndHook();
    }
}