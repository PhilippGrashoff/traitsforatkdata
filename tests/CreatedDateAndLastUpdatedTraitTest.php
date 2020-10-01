<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Model;
use atk4\data\Persistence;
use atk4\schema\Migration;
use traitsforatkdata\CreatedDateAndLastUpdatedTrait;


class CreatedDateAndLastUpdatedTraitTest extends TestCase {

    public function testCreatedDateAndLastUpdated() {

        $currentDateTime = new \DateTime();
        $model = $this->getTestModel();
        $model->save();

        self::assertEquals(
            $currentDateTime->format(DATE_ATOM),
            $model->get('created_date')->format(DATE_ATOM)
        );
        self::assertNull($model->get('last_updated'));

        sleep(1);

        $model->set('name', 'someName');
        $model->save();
        $newDateTime = new \DateTime();

        self::assertNotEquals(
            $newDateTime->format(DATE_ATOM),
            $model->get('created_date')->format(DATE_ATOM)
        );
        $newDateTime = new \DateTime();
        self::assertEquals(
            $newDateTime->format(DATE_ATOM),
            $model->get('last_updated')->format(DATE_ATOM)
        );
    }


    protected function getTestModel(): Model {
        $modelClass = new class() extends Model {

            use CreatedDateAndLastUpdatedTrait;

            public $table = 'sometable';

            protected function init(): void
            {
                parent::init();
                $this->addField('name');
                $this->addCreatedDateAndLastUpdateFields();
                $this->addCreatedDateAndLastUpdatedHook();
            }
        };


        $persistence = Persistence::connect('sqlite::memory:');
        $model = new $modelClass($persistence);
        Migration::of($model)->drop()->create();

        return $model;
    }
    /**/
}