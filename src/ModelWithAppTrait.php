<?php

declare(strict_types=1);

namespace traitsforatkdata;

use atk4\core\AppScopeTrait;

trait ModelWithAppTrait {

    use AppScopeTrait;

    public function __construct($persistence = null, $defaults = [])
    {
        if(isset($persistence->app)) {
            $this->app = $persistence->app;
        }
        parent::__construct($persistence, $defaults);
    }
}