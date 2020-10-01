<?php declare(strict_types=1);

namespace traitsforatkdata\tests;

use atk4\core\Exception;
use atk4\ui\App;
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