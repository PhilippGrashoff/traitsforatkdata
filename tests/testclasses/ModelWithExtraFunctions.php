<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Core\AppScopeTrait;
use Atk4\Data\Model;
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

        $this->hasOne('user_id', ['model' => [User::class]]);
    }
}