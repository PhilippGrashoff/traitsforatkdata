<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Data\Exception;
use Atk4\Data\Model;
use Atk4\Data\Persistence;
use traitsforatkdata\TestCase;
use traitsforatkdata\CachedModelTrait;


class CachedModelTraitTest extends TestCase {

    public function testModelsOnlyLoadedOnce() {
        $persistence = new Persistence\Array_();
        $otherModel1 = $this->getOtherTestModel($persistence);
        $otherModel1->save();
        $otherModel2 = $this->getOtherTestModel($persistence);
        $otherModel2->save();

        $model = $this->getTestModel($persistence);
        $res = $model->getCachedModel(get_class($otherModel1));
        //as values are cached it should not be in result
        $otherModel3 = $this->getOtherTestModel($persistence);
        $otherModel3->save();

        $res = $model->getCachedModel(get_class($otherModel1));
        self::assertCount(
            2,
            $res
        );

        $model->unsetCachedModel(get_class($otherModel1));
        $res = $model->getCachedModel(get_class($otherModel1));
        self::assertCount(
            3,
            $res
        );
    }

    public function testExceptionUndefinedModelClass() {
        $model = $this->getTestModel();
        self::expectException(Exception::class);
        $res = $model->getCachedModel('SomeNonExistantClass');
    }

    protected function getTestModel(Persistence $persistence = null): Model {
        $class = new class() extends Model {

            use CachedModelTrait;

            public $table = 'some_table';
        };

        return new $class($persistence? : new Persistence\Array_());
    }

    protected function getOtherTestModel(Persistence $persistence = null): Model {
        $class = new class() extends Model {

            public $table = 'some_other_table';
        };

        return new $class($persistence? : new Persistence\Array_());
    }
}
