<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Data\Model;
use traitsforatkdata\CachedModelTrait;

class ModelWithCachedModelTrait extends Model
{
    use CachedModelTrait;

    public $table = 'ModelWithCachedModelTrait';
}