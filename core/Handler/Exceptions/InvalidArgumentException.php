<?php
/**
 * @package     core/Handler
 * @subpackage  Exceptions
 * @file        InvalidArgumentException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:15:57
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Handler\Exceptions;

use \Exception;

class InvalidArgumentException extends Exception {
   public function __construct(string $message = 'Invalid argument', int $code = 0, Exception $previous = null) {
      parent::__construct($message, $code, $previous);
   }
}