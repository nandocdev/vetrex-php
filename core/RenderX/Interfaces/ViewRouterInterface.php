<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        ViewRouterInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:59
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface ViewRouterInterface {
   /**
    * Convierte un namespace de controlador en una ruta de vista.
    *
    * @param string $controllerNamespace Namespace del controlador.
    * @return string La ruta de la vista.
    */
   public function getRouteFromNamespace(string $controllerNamespace): string;
}