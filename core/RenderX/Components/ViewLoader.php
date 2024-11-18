<?php
/**
 * @package     core/RenderX
 * @subpackage  Components
 * @file        ViewLoader
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-18 09:22:32
 * @version     1.0.0
 * @description: carga los archivos de vistas, templates, parciales y layouts
 * @license     MIT
 * @algoritmo:
 * 1. 
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Components;
use Vertex\Core\Handler\Config;
class ViewLoader {
   private string $templatesPath;
   private string $partialsPath;
   private string $layoutsPath;
   private Config $config;

   public function __construct() {
      $this->config = new Config();
   }

   public function loadTemplate(string $template = 'default'): string {
      $this->templatesPath = $this->config->get('path.templates');
      $file = $this->templatesPath . $template . DIRECTORY_SEPARATOR . 'index.template.phtml';
      if (!file_exists($file)) {
         throw new \InvalidArgumentException("El archivo de vista no existe: $file");
      }
      return $file;
   }



   public function loadLayout(string $pathModule, string $layout = 'index'): string {
      $this->layoutsPath = $this->config->get('path.root') . DIRECTORY_SEPARATOR . $pathModule . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $layout . '.view.phtml';
      if (!file_exists($this->layoutsPath)) {
         throw new \InvalidArgumentException("El archivo de layout no existe: $this->layoutsPath");
      }

      return $this->layoutsPath;
   }

   // basado en el layout actual, carga un partial
   public function loadPartial(string $partial, string $type = 'layout'): string {
      $partialPath = ($type === 'layout') ? $this->layoutsPath : $this->partialsPath;
      $file = $partialPath . DIRECTORY_SEPARATOR . $partial . '.partial.phtml';
      if (!file_exists($file)) {
         throw new \InvalidArgumentException("El archivo de partial no existe: $file");
      }
      return $file;
   }

}