<?php
/**
 * @package     vertex/core
 * @subpackage  Plugins
 * @file        Logger
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:27:16
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Plugins;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Vertex\Core\Interfaces\LoggerInterface;
use Vertex\Core\Handler\Config;
class Logger implements LoggerInterface {
   private $logger;

   public function __construct() {
      $cfg = new Config();
      $this->logger = new MonoLogger('vertex');
      $this->logger->pushHandler(new StreamHandler($cfg->get('path.tmp') . '/logs/vrtx_' . date('ymd') . '_.log'));
      $this->logger->pushProcessor(new UidProcessor());
   }

   public function info(string $message, array $context = []) {
      $this->logger->info($message, $context);
   }

   public function error(string $message, array $context = []) {
      $this->logger->error($message, $context);
   }

   public function warning(string $message, array $context = []) {
      $this->logger->warning($message, $context);
   }

   public function debug(string $message, array $context = []) {
      $this->logger->debug($message, $context);
   }

   public function critical(string $message, array $context = []) {
      $this->logger->critical($message, $context);
   }

   public function alert(string $message, array $context = []) {
      $this->logger->alert($message, $context);
   }

}