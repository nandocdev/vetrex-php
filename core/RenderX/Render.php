<?php
/**
 * @package     vertex/core
 * @subpackage  RenderX
 * @file        Render
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-19 11:24:39
 * @version     1.0.0
 * @description
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

namespace Vertex\Core\RenderX;

use Vertex\Core\Handler\Config;
use Vertex\Core\RenderX\Core\DataHandler;
use Vertex\Core\RenderX\Core\TemplateLoader;

class Render {
   private Config $config;
   private DataHandler $dataHandler;
   private TemplateLoader $templateLoader;

   /**
    * Summary of __construct
    * @param \Vertex\Core\Handler\Config $config
    * @param \Vertex\Core\RenderX\Core\DataHandler $dataHandler
    * @param \Vertex\Core\RenderX\Core\TemplateLoader $templateLoader
    */
   public function __construct(Config $config, DataHandler $dataHandler, TemplateLoader $templateLoader) {
      $this->config = $config;
      $this->dataHandler = $dataHandler;
      $this->templateLoader = $templateLoader;
   }

   /**
    * Summary of render
    * @param string $view
    * @param array $data
    * @param string $layout
    * @return void
    */
   public function render(string $viewPath, string $view, array $data, string $layout): void {
      $this->dataHandler->setData($data);
      $layout = $this->templateLoader->loadLayout($layout);
      $view = $this->templateLoader->loadView($view, $viewPath);
      print_r([$layout, $view]);
   }

}