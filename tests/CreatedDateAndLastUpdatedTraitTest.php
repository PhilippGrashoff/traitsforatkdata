<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Model;
use atk4\data\Persistence;
use atk4\schema\Migration;
use traitsforatkdata\CreatedDateAndLastUpdatedTrait;


class CreatedDateAndLastUpdatedTraitTest extends TestCase
{

    public function testCreatedDateAndLastUpdated()
    {
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

    /**
     * before, last_updated was set in before update hook. That caused models to be always saved even if
     * there was nothing to save. This was changed, this test ensures that this stays.
     */
    public function testNoFieldsDirtyNothingIsSaved()
    {
        $model = $this->getTestModel();
        $model->save();
        $lastUpdated = $model->get('last_updated');
        self::assertNull($lastUpdated);
        $model->save();
        self::assertSame(
            $model->get('last_updated'),
            $lastUpdated
        );
        $model->set('name', 'somename');
        $model->save();
        self::assertSame(
            'somename',
            $model->get('name')
        );
        self::assertInstanceOf(
            \DateTimeInterface::class,
            $model->get('last_updated')
        );
    }


    protected function getTestModel(): Model
    {
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