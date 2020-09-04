<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\AtkPhpunit\TestCase;
use atk4\data\Model;
use traitsforatkdata\UniqueFieldTrait;
use atk4\data\Exception;
use atk4\data\Persistence;
use atk4\schema\Migration;


class UniqueFieldTraitTest extends TestCase
{

    public function testExceptionOnEmptyValue()
    {
        $model = $this->getTestModel();
        self::expectException(Exception::class);
        $model->isFieldUnique('unique_field');
    }

    public function testReturnFalseIfOtherRecordWithSameUniqueFieldValueExists()
    {
        //use SQL Persistence here as it supports conditions
        $persistence = Persistence::connect('sqlite::memory:');
        $model = $this->getTestModel($persistence);
        Migration::of($model)->drop()->create();
        $model->set('unique_field', 'ABC');
        $model->save();
        self::assertTrue($model->isFieldUnique('unique_field'));

        $model2 = $this->getTestModel($persistence);
        $model2->save();
        $model2->set('unique_field', 'DEF');
        self::assertTrue($model2->isFieldUnique('unique_field'));
        $model2->set('unique_field', 'ABC');
        $otherModel = $this->getTestModel($persistence);
        self::assertFalse($model2->isFieldUnique('unique_field'));
    }

    protected function getTestModel(Persistence $persistence = null): Model
    {
        $modelClass = new class() extends Model {

            use UniqueFieldTrait;

            public $table = 'sometable';

            public function init(): void
            {
                parent::init();
                $this->addField('unique_field');
            }
        };

        return new $modelClass($persistence ?: new Persistence\Array_());
    }
}
