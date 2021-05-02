<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Core\AppScopeTrait;
use Atk4\Data\Model;
use traitsforatkdata\CreatedByTrait;

class User extends Model {

    use CreatedByTrait;

    public $table = 'user';


    protected function init(): void
    {
        parent::init();
        $this->addField('name');
    }
}