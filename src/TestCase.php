<?php

declare(strict_types=1);

namespace traitsforatkdata;

use atk4\data\Persistence;
use atk4\schema\Migration;
use atk4\ui\App;

class TestCase extends \atk4\core\AtkPhpunit\TestCase
{

    protected $sqlitePersistenceModels = [];

    protected function getSqliteTestPersistence(array $additionalClasses = [], App $app = null): Persistence
    {
        $allClasses = array_merge($this->sqlitePersistenceModels, $additionalClasses);
        $persistence = Persistence::connect('sqlite::memory:');
        if($app) {
            $persistence->app = $app;
        }
        foreach ($allClasses as $className) {
            $model = new $className($persistence);
            Migration::of($model)->drop()->create();
        }

        return $persistence;
    }
}