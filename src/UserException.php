<?php declare(strict_types=1);

namespace traitsforatkdata;

use Atk4\Core\Exception;


/**
 * Throw this exception if the content should be shown to the end user. This class is introduced to
 * differ from "system" Exceptions which are only if interest for developers.
 */
class UserException extends Exception {

}