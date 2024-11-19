<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        TemplateLoader
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-19 11:28:36
 * @version     1.0.0
 * @glosary
 * - Render: Clase principal para renderizar vistas
 * - Layout: Plantilla base de la vista
 * - LayoutPath: Ruta de la plantilla base
 * - View: Vista principal
 * - ViewPath: Ruta de la vista principal
 * - Component: Fragmento de vista que se puede reutilizar
 * - Partial: Fragmento de vista que se repite en varias vistas
 * - Template: Cualquier archivo que contenga código phtml, html, php
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;

use Vertex\Core\Handler\Config;

class TemplateLoader {
   private Config $config;
   private array $partialsPath = [];
   private string $layoutsPath;
   private string $viewsPath;
   private string $layout;
   private string $view;
   const DS = DIRECTORY_SEPARATOR;

   public function __construct(Config $config) {
      $this->config = $config;
      $this->layoutsPath = $this->config->get('path.layouts');
      $this->partialsPath = [];
      $this->viewsPath = '';
   }

   /**
    * Summary of loadLayout
    * @param string $layout nombre del layout
    * @throws \InvalidArgumentException 
    * @return string
    */
   public function loadLayout(string $layout): string {
      $this->layoutsPath = $this->config->get('path.layouts');
      $this->layout = $this->layoutsPath . $layout . DS . 'index.layout.phtml';
      if (!file_exists($this->layout)) {
         throw new \InvalidArgumentException("El archivo de layout no existe: $this->layout");
      }
      return $this->layout;
   }

   /**
    * Summary of loadView
    * @param string $view ruta relativa de la vista
    * @param string $viewpath ruta base dinamica de la vista
    * @return string ruta absoluta de la vista
    */
   public function loadView(string $view, string $viewpath): string {
      $this->viewsPath = $this->config->get('path.root') . DS . $viewpath . DS . 'Views' . DS;
      $this->view = $this->viewsPath . $view . '.view.phtml';
      if (!file_exists($this->view)) {
         throw new \InvalidArgumentException("El archivo de vista no existe: $this->view");
      }
      return $this->view;
   }

   /**
    * Summary of loadPartial
    * @param string $partial nombre del partial
    * @param string $type tipo de partial (layout o view)
    * @throws \InvalidArgumentException
    * @return string
    */
   // public function loadPartial(string $partial, string $type): string {
   //    $this->partialsPath = $this->config->get('path.partials');
   //    $partialPath = ($type === 'layout') ? $this->layoutsPath : $this->partialsPath;
   //    $file = $partialPath . DS . 'partials' . DS . $partial . '.partial.phtml';
   //    if (!file_exists($file)) {
   //       throw new \InvalidArgumentException("El archivo de partial no existe: $file");
   //    }
   //    return $file;
   // }

}