<?php
/**
 * @package     core/VRouter
 * @subpackage  Matcher
 * @file        RouteParamsExtractor
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:55:07
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Matcher;

class RouteParamsExtractor {
   public function extractParams(string $uri, string $pathPattern): ?array {
      $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\??\}/', '([a-zA-Z0-9_]+)?', $pathPattern);
      $pathRegex = "#^" . $pathRegex . "$#";
      if (preg_match($pathRegex, $uri, $matches)) {
         array_shift($matches); // Eliminar la ruta completa
         return $matches;
      }
      return null;
   }
}