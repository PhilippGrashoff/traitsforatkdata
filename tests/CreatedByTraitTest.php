<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\ui\App;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\ModelWithCreatedByTrait;
use traitsforatkdata\tests\testclasses\User;


class CreatedByTraitTest extends TestCase {

    public function testCreatedBy()
    {
        $persistence = $this->getSqliteTestPersistence([ModelWithCreatedByTrait::class, User::class]);
        $model = new ModelWithCreatedByTrait($persistence);
        $app = new App(['always_run' => false]);
        $app->auth = new \stdClass();
        $app->auth->user = new User($persistence);
        $app->auth->user->set('name', 'SOMENAME');
        $app->auth->user->save();

        $model->app = $app;

        $model->save();

        self::assertEquals(
            $app->auth->user->get('id'),
            $model->get('created_by')
        );

        //call again to cover missing line
        $model->save();
    }

    /**/
}