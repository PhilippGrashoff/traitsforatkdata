<?php

declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Model;
use atk4\data\Persistence;
use atk4\ui\App;
use traitsforatkdata\ModelWithAppTrait;

class ModelWithAppTraitTest extends TestCase {

    public function testAppIsPresent() {
        $persistence = Persistence::connect('sqlite::memory:');
        $persistence->app = new App(['always_run' => false]);
        $model = $this->getTestModel($persistence);
        self::assertInstanceOf(
            App::class,
            $model->app
        );
    }

    public function testWorksAlsoWithPersistenceWithoutApp() {
        $persistence = Persistence::connect('sqlite::memory:');
        $model = $this->getTestModel($persistence);
        self::assertNull(
            $model->app
        );
    }

    protected function getTestModel(Persistence $persistence): Model {
        $model = new class() extends Model {

            use ModelWithAppTrait;

            public $table = 'sometable';
            protected function init(): void {
                parent::init();
                $this->addField('somefield');
            }
        };

        return new $model($persistence);
    }
}