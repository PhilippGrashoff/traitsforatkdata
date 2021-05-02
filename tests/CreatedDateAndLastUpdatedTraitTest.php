<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use traitsforatkdata\TestCase;
use Atk4\Data\Model;
use Atk4\Data\Persistence;
use atk4\schema\Migration;
use traitsforatkdata\CreatedDateAndLastUpdatedTrait;
use traitsforatkdata\tests\testclasses\ModelWithCreatedDateAndLastUpdatedTrait;


class CreatedDateAndLastUpdatedTraitTest extends TestCase
{

    protected $sqlitePersistenceModels = [ModelWithCreatedDateAndLastUpdatedTrait::class];

    public function testCreatedDateAndLastUpdated()
    {
        $currentDateTime = new \DateTime();
        $model = new ModelWithCreatedDateAndLastUpdatedTrait($this->getSqliteTestPersistence());
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
        $model = new ModelWithCreatedDateAndLastUpdatedTrait($this->getSqliteTestPersistence());
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

    public function testSetCreatedDateNotOverwritten() {
        $model = new ModelWithCreatedDateAndLastUpdatedTrait($this->getSqliteTestPersistence());
        $model->set('created_date', (new \DateTime())->modify('-1 Month'));
        $model->save();

        self::assertEquals(
            (new \DateTime())->modify('-1 Month')->getTimestamp(),
            $model->get('created_date')->getTimestamp()
        );
    }
}