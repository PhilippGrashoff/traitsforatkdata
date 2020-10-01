<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use atk4\core\AppScopeTrait;
use atk4\data\Model;
use traitsforatkdata\CreatedByTrait;

class User extends Model {

    use CreatedByTrait;
    use AppScopeTrait;

    public $table = 'user';


    protected function init(): void
    {
        parent::init();
        $this->addField('name');
    }
}