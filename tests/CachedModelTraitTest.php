<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Data\Exception;
use Atk4\Data\Model;
use Atk4\Data\Persistence;
use traitsforatkdata\CachedModelTrait;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\ModelWithCachedModelTrait;
use traitsforatkdata\tests\testclasses\ModelWithDateTimeHelpersTrait;
use traitsforatkdata\tests\testclasses\User;


class CachedModelTraitTest extends TestCase
{

    public function testModelsOnlyLoadedOnce()
    {
        $persistence = $this->getSqliteTestPersistence([User::class]);
        $user1 = (new User($persistence))->createEntity();
        $user1->save();
        $user2 = (new User($persistence))->createEntity();
        $user2->save();

        $model = (new ModelWithCachedModelTrait($persistence))->createEntity();
        $model->getCachedModel(User::class);
        //as values are cached it should not be in result
        $user3 = (new User($persistence))->createEntity();
        $user3->save();

        $res = $model->getCachedModel(User::class);
        self::assertCount(
            2,
            $res
        );

        $model->unsetCachedModel(User::class);
        $res = $model->getCachedModel(User::class);
        self::assertCount(
            3,
            $res
        );
    }

    public function testExceptionUndefinedModelClass()
    {
        $model = (new ModelWithCachedModelTrait())->createEntity();
        self::expectException(Exception::class);
        $model->getCachedModel('SomeNonExistantClass');
    }
}
