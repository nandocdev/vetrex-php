<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        LayoutRenderer
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:20:35
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

use Vertex\Core\View\DataHandler;
use Vertex\Core\View\PartialLoader;
use Vertex\Core\View\ResourceManager;
use Vertex\Core\View\DebugHandler;
use Vertex\Core\Handler\Exceptions\FileNotFoundException;

class LayoutRenderer {
   private DataHandler $dataHandler;
   private PartialLoader $partialLoader;
   private ResourceManager $resourceManager;
   private DebugHandler $debugHandler;
   private string $layoutPath;
   private string $viewPath;
   private string $layout = 'default';
   private array $layoutCache = [];

   public function __construct(
      DataHandler $dataHandler,
      PartialLoader $partialLoader,
      ResourceManager $resourceManager,
      DebugHandler $debugHandler,
      array $config = []
   ) {
      $this->dataHandler = $dataHandler;
      $this->partialLoader = $partialLoader;
      $this->resourceManager = $resourceManager;
      $this->debugHandler = $debugHandler;
      $this->viewPath = $config['viewPath'] ?? 'views/';
      $this->layoutPath = $config['layoutPath'] ?? 'layouts/';
   }

   public function setLayout(string $layout) {
      $this->layout = $layout;
   }

   public function render(string $view, array $data = []): string {
      if (!empty($data)) {
         $this->dataHandler->setData($data);
      }

      $viewPath = $this->viewPath . $view . '.php';
      $layoutPath = $this->layoutPath . $this->layout . '.php';

      if (!file_exists($viewPath)) {
         throw new FileNotFoundException("Vista no encontrada: $viewPath");
      }

      if (!file_exists($layoutPath)) {
         throw new FileNotFoundException("Layout no encontrado: $layoutPath");
      }

      // Renderizar vista
      $viewContent = $this->renderView($viewPath, $this->dataHandler->prepareDataForView());

      // Obtener contenido del layout
      $layoutContent = $this->getLayoutContent($layoutPath);

      // Procesar marcadores
      $renderedContent = $this->processMarkers($layoutContent, $viewContent);

      return $renderedContent;
   }

   public function renderPartial(string $partial, array $data) {
      return $this->partialLoader->import($partial, $data, true);
   }

   public function setLayoutPath(string $path) {
      $this->layoutPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
   }

   private function renderView(string $viewPath, array $data): string {
      ob_start();
      extract($data, EXTR_SKIP);
      include $viewPath;
      return ob_get_clean();
   }

   private function getLayoutContent(string $layoutPath): string {
      if (!isset($this->layoutCache[$layoutPath])) {
         ob_start();
         include $layoutPath;
         $this->layoutCache[$layoutPath] = ob_get_clean();
      }
      return $this->layoutCache[$layoutPath];
   }

   private function processMarkers(string $content, string $viewContent): string {
      $markers = [
         '@content' => $viewContent,
         '@styles' => $this->resourceManager->renderStyles(),
         '@scripts' => $this->resourceManager->renderScripts(),
      ];
      foreach ($markers as $key => $value) {
         $content = str_replace($key, $value, $content);
      }
      return $content;
   }
}