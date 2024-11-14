<?php
/**
 * @package     core/VQuery
 * @subpackage  Exceptions
 * @file        DatabaseConnectionException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:09:54
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Exceptions;

class DatabaseConnectionException extends \PDOException {
   public function __construct(string $message = 'Database connection error', int $code = 0, \Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
   }

}