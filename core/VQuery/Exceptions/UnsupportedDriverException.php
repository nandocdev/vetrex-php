<?php
/**
 * @package     core/VQuery
 * @subpackage  Exceptions
 * @file        UnsupportedDriverException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:10:35
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Exceptions;

class UnsupportedDriverException extends \InvalidArgumentException {
   public function __construct(string $message = 'Unsupported driver', int $code = 0, \Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
   }
}