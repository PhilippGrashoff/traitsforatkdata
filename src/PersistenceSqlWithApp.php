<?php

declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Core\AppScopeTrait;
use Atk4\Data\Persistence;

class PersistenceSqlWithApp extends Persistence\Sql
{
    use AppScopeTrait;
}