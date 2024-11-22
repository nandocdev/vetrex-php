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
      $this->layout = '';
      $this->view = '';
   }

   public function loadLayout(string $layout): string {
      $this->layoutsPath = $this->config->get('path.layouts');
      $this->layout = $this->layoutsPath . $layout . self::DS . 'index.layout.phtml';
      if (!file_exists($this->layout)) {
         throw new \InvalidArgumentException("El archivo de layout no existe: $this->layout");
      }
      return $this->layout;
   }


   public function loadView(string $view, string $viewpath): string {
      $this->viewsPath = $this->config->get('path.root') . self::DS . $viewpath . self::DS . 'Views' . self::DS;
      $this->view = $this->viewsPath . $view . '.view.phtml';
      if (!file_exists($this->view)) {
         throw new \InvalidArgumentException("El archivo de vista no existe: $this->view");
      }
      return $this->view;
   }


   public function loadPartial(string $partial, string $type): string {
      $partialsPath = $type === 'view' ? $this->view : $this->layout;
      // elimina el nombre del archivo para obtener la ruta del directorio
      $partialsPath = substr($partialsPath, 0, strrpos($partialsPath, self::DS) + 1);
      $partial = $partialsPath . 'partials' . self::DS . $partial . '.partial.phtml';
      if (!file_exists($partial)) {
         throw new \InvalidArgumentException("El archivo de partial no existe: $partial");
      }
      return $partial;
   }

   // getters
   public function getLayout(): string {
      return $this->layout;
   }

   public function getView(): string {
      return $this->view;
   }

   public function getPartialsPath(): array {
      return $this->partialsPath;
   }

   public function getLayoutsPath(): string {
      return $this->layoutsPath;
   }

   public function getViewsPath(): string {
      return $this->viewsPath;
   }



}