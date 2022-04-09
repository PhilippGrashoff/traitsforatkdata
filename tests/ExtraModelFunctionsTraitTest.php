<?php

declare(strict_types=1);

namespace traitsforatkdata\tests;


use Atk4\Data\Exception;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\ModelWithExtraFunctions;
use traitsforatkdata\tests\testclasses\User;

class ExtraModelFunctionsTraitTest extends TestCase
{

    protected $sqlitePersistenceModels = [
        ModelWithExtraFunctions::class,
        User::class
    ];

    public function testAtLeastOneFieldDirty()
    {
        $model = (new ModelWithExtraFunctions($this->getSqliteTestPersistence()))->createEntity();
        $model->save();
        self::assertFalse($model->isAtLeastOneFieldDirty(['name', 'firstname', 'lastname']));
        $model->set('lastname', 'SOMENAME');
        self::assertTrue($model->isAtLeastOneFieldDirty(['name', 'firstname', 'lastname']));
    }

    public function testExceptionIfThisNotLoaded()
    {
        $model = (new ModelWithExtraFunctions($this->getSqliteTestPersistence()))->createEntity();
        $model->save();
        $this->callProtected($model, '_exceptionIfThisNotLoaded', []);
        $model->unload();
        self::expectException(Exception::class);
        $this->callProtected($model, '_exceptionIfThisNotLoaded', []);
    }

    public function testLoadedHasOneRef()
    {
        $persistence = $this->getSqliteTestPersistence();
        $model = (new ModelWithExtraFunctions($persistence))->createEntity();
        $model->save();
        $referencedModel = (new User($persistence))->createEntity();
        $referencedModel->save();
        $model->set('user_id', $referencedModel->getId());
        $ref = $model->loadedHasOneRef('user_id');
        self::assertEquals(
            $referencedModel->getId(),
            $ref->getId()
        );
        $referencedModel->delete();
        self::expectException(Exception::class);
        $model->loadedHasOneRef('user_id');
    }

    public function testLoadedHasOneRefFieldEmpty()
    {
        $model = (new ModelWithExtraFunctions($this->getSqliteTestPersistence()))->createEntity();
        $model->save();
        self::expectException(Exception::class);
        $model->loadedHasOneRef('user_id');
    }

}