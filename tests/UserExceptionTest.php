<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use Atk4\Core\Exception;
use Atk4\Ui\App;
use traitsforatkdata\TestCase;
use traitsforatkdata\UserException;


class UserExceptionTest extends TestCase {

    public function testConstruct() {
        $userException = new UserException();
        self::assertInstanceOf(
            Exception::class,
            $userException
        );
    }
}