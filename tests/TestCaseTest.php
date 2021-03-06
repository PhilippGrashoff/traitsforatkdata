<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Ui\App;
use traitsforatkdata\TestCase;


class TestCaseTest extends TestCase {

    public function testSetAppGetSqlitePersistence() {
        $persistence = $this->getSqliteTestPersistence([], new App(['always_run' => false]));
        self::assertInstanceOf(App::class, $persistence->getApp());
    }
}