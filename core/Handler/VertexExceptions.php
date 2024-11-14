<?php
/**
 * @package     vertex/core
 * @subpackage  Handler
 * @file        VertexExceptions
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:24:34
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Handler;
use Vertex\Core\Plugins\Logger;
class VertexExceptions {
   public static function errorHandler(int $errno, string $errstr, string $errfile, int $errline): void {
      if (!(error_reporting() && $errno)) {
         return;
      }

      $errstr = htmlspecialchars($errstr, ENT_QUOTES, 'UTF-8');
      $message = '[Bundle Error]: ';
      switch ($errno) {
         case E_USER_ERROR:
            $message .= "<b>ERROR</b> [$errno] $errstr<br/>\n";
            $message .= "Error fatal en la linea $errline en el archivo $errfile";
            $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br/>\n";
            $message .= "Abortando...<br/>\n";
            break;

         case E_USER_WARNING:
            $message .= "<b>WARNING</b> [$errno] $errstr<br/>\n";
            $message .= "Advertencia en la linea $errline en el archivo $errfile";
            $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br/>\n";
            break;

         case E_USER_NOTICE:
            $message .= "<b>NOTICE</b> [$errno] $errstr<br/>\n";
            $message .= "Notoficacion en la linea $errline en el archivo $errfile";
            $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br/>\n";
            break;

         default:
            $message .= "Tipo de error desconocido: [$errno] $errstr<br/>\n";
            $message .= "Error en la linea $errline en el archivo $errfile";
            $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br/>\n";
            break;
      }

      throw new \ErrorException($message, $errno);
   }

   public static function exceptionHandler($e): void {

      $code = $e->getCode();
      $logger = new Logger();
      $data = [];
      $data['trace'] = $e->getTrace();
      $data['traceString'] = $e->getTraceAsString();
      $data['file'] = $e->getFile();
      $data['line'] = $e->getLine();
      $data['class'] = get_class($e);

      // si el codigo de error esta comprendido entre 400 y 499, se trata de un error de cliente
      if ($code >= 400 && $code <= 499) {
         header("HTTP/1.1 $code Not Found");
      }

      // si el codigo de error esta comprendido entre 500 y 599, se trata de un error de servidor
      if ($code >= 500 && $code <= 599) {
         header("HTTP/1.1 $code Internal Server Error");
      }
      switch ($code) {
         case 404:
            $logger->error($e->getMessage());
            $message = 'Error: 404 - ';
            $title = 'No se encontro la pagina';
            break;

         case 500:
            $logger->critical($e->getMessage());
            $message = 'Error: 500 -';
            $title = 'Error interno del servidor';
            break;

         default:
            $logger->warning($e->getMessage());
            $message = 'Error: ';
            $title = 'Error en la aplicacion #' . $code;
            break;
      }
      $data['title'] = $title;
      $data['message'] = $message . ' ' . $e->getMessage();
      $data['code'] = $code;

      $logger->error($data['message'], $data);
      if (!defined('ERROR_HANDLER')) {
         define('ERROR_HANDLER', true);
      }
   }
}