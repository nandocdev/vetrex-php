<?php
/**
 * @package     core/Handler
 * @subpackage  Exceptions
 * @file        FileNotFoundException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:17:50
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Handler\Exceptions;
use \Exception;

class FileNotFoundException extends Exception {
   public function __construct($message = "Archivo no encontrado", $code = 0, Exception $previous = null) {
      parent::__construct($message, $code, $previous);
   }
}