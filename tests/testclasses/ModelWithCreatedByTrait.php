<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use atk4\core\AppScopeTrait;
use atk4\data\Model;
use traitsforatkdata\CreatedByTrait;

class ModelWithCreatedByTrait extends Model {

    use CreatedByTrait;
    use AppScopeTrait;

    public $table = 'sometable';

    protected function init(): void
    {
        parent::init();
        $this->addField('somefield');
        $this->addCreatedByFields();
        $this->addCreatedByHook();
    }
}