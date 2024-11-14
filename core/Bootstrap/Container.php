<?php
/**
 * @package     vertex/core
 * @subpackage  Bootstrap
 * @file        Container
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 21:42:05
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Bootstrap;

use DI\ContainerBuilder;
use DI\Container as DIContainer;

class Container {

   public static function build(): DIContainer {
      $containerBuilder = new ContainerBuilder();
      $containerBuilder->useAutowiring(true);
      // $containerBuilder->useAnnotations(false); // Method does not exist
      $containerBuilder->addDefinitions(__DIR__ . '/../../config/definitions.php');
      try {
         return $containerBuilder->build();
      } catch (\Exception $e) {

         die($e->getMessage());
      }
   }
}