<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Data\Persistence;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\ModelWithDateTimeHelpersTrait;


class DateTimeHelperTraitTest extends TestCase
{

    public function testGetDiffMinutes()
    {
        $d1 = new \DateTime();
        $d2 = clone $d1;
        $dth = $this->getTestModel();
        self::assertEquals(
            0,
            $dth->getDateDiffTotalMinutes($d1, $d2)
        );
        $d2->modify('+100 Days');
        self::assertEquals(100 * 24 * 60, $dth->getDateDiffTotalMinutes($d1, $d2));
    }

    public function testDateCasting()
    {
        $dth = $this->getTestModel();
        self::assertEquals(
            (new \DateTime())->format('d.m.Y H:i:s'),
            $dth->castDateTimeToGermanString($dth, 'datetime')
        );
        self::assertEquals(
            (new \DateTime())->format('d.m.Y'),
            $dth->castDateTimeToGermanString($dth, 'date')
        );
        self::assertEquals(
            (new \DateTime())->format('H:i:s'),
            $dth->castDateTimeToGermanString($dth, 'time')
        );
        self::assertEquals(
            '',
            $dth->castDateTimeToGermanString($dth, 'some_other_field')
        );
    }

    public function testShortenTime()
    {
        $dth = $this->getTestModel();
        self::assertEquals(
            (new \DateTime())->format('d.m.Y H:i'),
            $dth->castDateTimeToGermanString($dth, 'datetime', true)
        );
        self::assertEquals(
            (new \DateTime())->format('H:i'),
            $dth->castDateTimeToGermanString($dth, 'time', true)
        );
    }

    public function testNoDateTimeInterFaceValue()
    {
        $dth = $this->getTestModel();
        $dth->set('some_other_field', 'lala');
        self::assertEquals(
            'lala',
            $dth->castDateTimeToGermanString($dth, 'some_other_field')
        );
    }


    protected function getTestModel(Persistence $persistence = null): ModelWithDateTimeHelpersTrait
    {
        $entity = (new ModelWithDateTimeHelpersTrait($persistence ?: $this->getSqliteTestPersistence()))->createEntity(
        );
        $entity->set('datetime', new \DateTime());
        $entity->set('date', new \DateTime());
        $entity->set('time', new \DateTime());

        return $entity;
    }
}
