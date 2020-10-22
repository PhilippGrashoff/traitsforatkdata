<?php

declare(strict_types=1);

namespace traitsforatkdata;

use atk4\core\AppScopeTrait;
use atk4\data\Persistence;

class PersistenceSqlWithApp extends Persistence\Sql {

    use AppScopeTrait;
}