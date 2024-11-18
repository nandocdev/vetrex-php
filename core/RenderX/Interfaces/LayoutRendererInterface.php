<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        LayoutRendererInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:43
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface LayoutRendererInterface {
   /**
    * Renderiza una vista usando un layout.
    *
    * @param string $viewPath Ruta completa a la vista.
    * @param array $data Datos a pasar a la vista.
    * @param string $template Nombre del template a usar (opcional).
    * @return string El contenido renderizado.
    * @throws \Vertex\Core\Handler\Exceptions\FileNotFoundException Si la vista o el layout no se encuentran.
    */
   public function render(string $viewPath, array $data = [], string $template = 'index.template.phtml'): string;

   /**
    * Renderiza un partial.
    * @param string $partial
    * @param array $data
    * @return string
    */
   public function renderPartial(string $partial, array $data): string;

   /**
    * Establece la ruta de los layouts.
    * @param string $path
    * @return void
    */
   public function setLayoutPath(string $path): void;

   /**
    * Establece el layout a usar.
    * @param string $layout
    * @return void
    */
   public function setLayout(string $layout): void;
}