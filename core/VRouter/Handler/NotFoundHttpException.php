<?php
/**
 * @package     core/VRouter
 * @subpackage  Handler
 * @file        NotFoundHttpException
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 23:10:47
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Handler;
use \Exception;
class NotFoundHttpException extends Exception {
   protected $statusCode;

   public function __construct($message = 'Not Found', $code = 404, Exception $previous = null) {
      // Establece el código de estado HTTP 404 como valor por defecto
      $this->statusCode = $code;

      // Llama al constructor de la clase base (Exception)
      parent::__construct($message, $code, $previous);
   }

   // Método para obtener el código de estado HTTP
   public function getStatusCode() {
      return $this->statusCode;
   }

   // Método para obtener una representación en formato JSON de la excepción (útil para APIs)
   public function toArray() {
      return [
         'error' => $this->getMessage(),
         'status_code' => $this->statusCode
      ];
   }
}