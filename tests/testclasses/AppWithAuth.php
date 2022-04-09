<?php

declare(strict_types=1);

namespace traitsforatkdata\tests\testclasses;

use Atk4\Data\Model;
use Atk4\Ui\App;
use traitsforatkdata\CreatedByTrait;

class AppWithAuth extends App
{
    public $auth;
}