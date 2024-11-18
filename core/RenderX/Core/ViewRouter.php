<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        ViewRouter
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:46:42
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;
use Vertex\Core\RenderX\Interfaces\ViewRouterInterface;
use InvalidArgumentException;

class ViewRouter implements ViewRouterInterface {
   /**
    * Convierte un namespace de controlador en una ruta de vista.
    *
    * @param string $controllerNamespace Namespace del controlador.  Ejemplo: `App\Controllers\Admin\Products`
    * @return string La ruta de la vista. Ejemplo: `admin/products/`
    * @throws InvalidArgumentException Si el namespace no es válido.
    */
   public function getRouteFromNamespace(string $controllerNamespace): string {
      //Validación básica del namespace
      if (empty($controllerNamespace) || !is_string($controllerNamespace)) {
         throw new InvalidArgumentException("El namespace del controlador no puede estar vacío y debe ser una cadena.");
      }

      //Reemplaza los backslashes por barras diagonales y elimina el prefijo del namespace (ej. 'App\Controllers\')
      //El prefijo se asume configurable, en este caso se usa 'App\Controllers\'
      $prefix = 'App\\Controllers\\'; //Este prefijo debería ser configurable

      $route = str_replace('\\', '/', substr($controllerNamespace, strlen($prefix)));


      //Asegura que la ruta termine con una barra diagonal
      return rtrim($route, '/') . '/';
   }
}