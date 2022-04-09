<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Ui\App;
use traitsforatkdata\TestCase;
use traitsforatkdata\tests\testclasses\AppWithAuth;
use traitsforatkdata\tests\testclasses\ModelWithCreatedByTrait;
use traitsforatkdata\tests\testclasses\User;


class CreatedByTraitTest extends TestCase {

    public function testCreatedBy()
    {
        $app = new AppWithAuth(['always_run' => false]);
        $persistence = $this->getSqliteTestPersistence([ModelWithCreatedByTrait::class, User::class], $app);
        $app->auth = new \stdClass();
        $app->auth->user = (new User($persistence))->createEntity();
        $app->auth->user->set('name', 'SOMENAME');
        $app->auth->user->save();

        $model = (new ModelWithCreatedByTrait($persistence))->createEntity();
        $model->save();

        self::assertEquals(
            $app->auth->user->getId(),
            $model->get('created_by')
        );

        //call again to cover missing line
        $model->save();
    }
}