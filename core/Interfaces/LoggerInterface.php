<?php
/**
 * @package     vertex/core
 * @subpackage  Interfaces
 * @file        LoggerInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:26:08
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Interfaces;



interface LoggerInterface {
   public function info(string $message, array $context = []);
   public function error(string $message, array $context = []);
   public function warning(string $message, array $context = []);
   public function debug(string $message, array $context = []);
   public function critical(string $message, array $context = []);
   public function alert(string $message, array $context = []);

}