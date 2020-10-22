<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use atk4\core\AppScopeTrait;
use atk4\data\Model;
use traitsforatkdata\CreatedByTrait;
use traitsforatkdata\ExtraModelFunctionsTrait;

class ModelWithExtraFunctions extends Model {

    use ExtraModelFunctionsTrait;

    public $table = 'model_with_extra_functions';


    protected function init(): void
    {
        parent::init();
        $this->addField('name');
        $this->addField('firstname');
        $this->addField('lastname');

        $this->hasOne('user_id', [User::class]);
    }
}