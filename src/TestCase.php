<?php

declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Data\Persistence;
use Atk4\Data\Schema\Migrator;
use Atk4\Ui\App;

class TestCase extends \Atk4\Core\Phpunit\TestCase
{

    protected $sqlitePersistenceModels = [];

    protected function getSqliteTestPersistence(array $additionalClasses = [], App $app = null): Persistence
    {
        $allClasses = array_merge($this->sqlitePersistenceModels, $additionalClasses);

        if ($app) {
            $persistence = new PersistenceSqlWithApp('sqlite::memory:');
            $persistence->setApp($app);
        } else {
            $persistence = new Persistence\Sql('sqlite::memory:');
        }

        $migration = new Migrator($persistence);
        foreach ($allClasses as $className) {
            $model = new $className($persistence);
            $migration->setModel($model);
            $migration->dropIfExists()->create();
        }

        return $persistence;
    }
}