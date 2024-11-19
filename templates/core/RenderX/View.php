<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        View.php
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 20:21:53
 * @version     1.0.0
 * @description Clase principal de RenderX encargada de gestionar el proceso completo 
 *              de renderizado de vistas. Incluye el manejo de datos, parciales y layouts, 
 *              además de integrarse con el gestor de caché y recursos.
 * @license     MIT
 * @algoritmo:
 *  
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX;

use Vertex\Core\RenderX\Components\RenderView;
use Vertex\Core\RenderX\Components\ViewLoader;
use Vertex\Core\RenderX\Components\DataHandler;
use Vertex\Core\Handler\Config;

class View {
   private RenderView $renderView;
   private ViewLoader $viewLoader;
   private DataHandler $dataHandler;
   private Config $config;
   private array $filesContent = [];
   private string $viewPath;
   private string $layoutPath;
   private string $partialPath;
   private array $markers = [];

   public function __construct(Config $config, DataHandler $dataHandler, ViewLoader $viewLoader, RenderView $renderView) {
      $this->config = $config;
      $this->dataHandler = $dataHandler;
      $this->viewLoader = $viewLoader;
      $this->renderView = $renderView;
   }


   public function setLayoutPath(string $layoutPath): self {
      $this->layoutPath = $this->viewLoader->loadTemplate($layoutPath);
      return $this;
   }

   public function setPartialPath(string $partialPath): self {
      $this->partialPath = $partialPath;
      return $this;
   }

   public function setViewPath(string $viewPath, string $viewName): self {
      $this->viewPath = $this->viewLoader->loadLayout($viewPath, $viewName);
      return $this;
   }




   private function mergeView() {
      $template = $this->renderView->load($this->layoutPath);
      $view = $this->renderView->load($this->viewPath);
      return str_replace('@content', $view, $template);
   }

   public function render(array $data = []): string {
      $content = $this->mergeView();
      return $this->renderView->compile($content, $data);
   }

}