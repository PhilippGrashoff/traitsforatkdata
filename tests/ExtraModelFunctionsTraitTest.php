<?php

declare(strict_types=1);

namespace traitsforatkdata\tests;


use atk4\data\Exception;
use traitsforatkdata\tests\testclasses\User;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\ModelWithExtraFunctions;

class ExtraModelFunctionsTraitTest extends TestCase {

    protected $sqlitePersistenceModels = [
        ModelWithExtraFunctions::class,
        User::class
    ];

    public function testAtLeastOneFieldDirty() {
        $model = new ModelWithExtraFunctions($this->getSqliteTestPersistence());
        $model->save();
        self::assertFalse($model->isAtLeastOneFieldDirty(['name', 'firstname', 'lastname']));
        $model->set('lastname', 'SOMENAME');
        self::assertTrue($model->isAtLeastOneFieldDirty(['name', 'firstname', 'lastname']));
    }

    public function testExceptionIfThisNotLoaded() {
        $model = new ModelWithExtraFunctions($this->getSqliteTestPersistence());
        $model->save();
        $this->callProtected($model, '_exceptionIfThisNotLoaded', []);
        $model->unload();
        self::expectException(Exception::class);
        $this->callProtected($model, '_exceptionIfThisNotLoaded', []);
    }

    public function testLoadedHasOneRef() {
        $persistence = $this->getSqliteTestPersistence();
        $model = new ModelWithExtraFunctions($persistence);
        $model->save();
        $referencedModel = new User($persistence);
        $referencedModel->save();
        $model->set('user_id', $referencedModel->get('id'));
        $ref = $model->loadedHasOneRef('user_id');
        self::assertEquals(
            $referencedModel->get('id'),
            $ref->get('id')
        );
        $referencedModel->delete();
        self::expectException(Exception::class);
        $model->loadedHasOneRef('user_id');
    }

    public function testLoadedHasOneRefFieldEmpty() {
        $model = new ModelWithExtraFunctions($this->getSqliteTestPersistence());
        $model->save();
        self::expectException(Exception::class);
        $model->loadedHasOneRef('user_id');
    }

}