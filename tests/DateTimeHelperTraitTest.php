<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Data\Model;
use Atk4\Data\Persistence;
use traitsforatkdata\DateTimeHelpersTrait;
use traitsforatkdata\TestCase;


class DateTimeHelperTraitTest extends TestCase
{

    public function testGetDiffMinutes(): void
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

    protected function getTestModel(): Model
    {
        $class = new class() extends Model {

            use DateTimeHelpersTrait;

            public $table = 'some_table';

            protected function init(): void
            {
                parent::init();
                $this->addField('datetime', ['type' => 'datetime']);
                $this->addField('date', ['type' => 'date']);
                $this->addField('time', ['type' => 'time']);
                $this->addField('some_other_field');

                $this->set('datetime', new \DateTime());
                $this->set('date', new \DateTime());
                $this->set('time', new \DateTime());
            }
        };

        $persistence = Persistence::connect('sqlite::memory:');
        $model = new $class($persistence);

        return $model;
    }
}
