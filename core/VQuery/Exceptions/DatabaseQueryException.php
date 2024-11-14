<?php
/**
 * @package     core/VQuery
 * @subpackage  Exceptions
 * @file        DatabaseQueryException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:23:53
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Exceptions;

class DatabaseQueryException extends \RuntimeException {
   public function __construct($message, $code = 0, \Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
   }
}