<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        View
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:25:14
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

use Vertex\Core\View\DataHandler;
use Vertex\Core\View\PartialLoader;
use Vertex\Core\View\ResourceManager;
use Vertex\Core\View\LayoutRenderer;
use Vertex\Core\View\CacheManager;
use Vertex\Core\Handler\Exceptions\FileNotFoundException;

class View {
   private DataHandler $dataHandler;
   private PartialLoader $partialLoader;
   private ResourceManager $resourceManager;
   private DebugHandler $debugHandler;
   private LayoutRenderer $layoutRenderer;
   private CacheManager $cacheManager;

   private bool $cacheEnabled;

   public function __construct(bool $debugMode = false, bool $cacheEnabled = true) {
      $this->dataHandler = new DataHandler();
      $this->resourceManager = new ResourceManager();
      $this->debugHandler = new DebugHandler($debugMode);
      $this->partialLoader = new PartialLoader($this->dataHandler);
      $this->layoutRenderer = new LayoutRenderer(
         $this->dataHandler, $this->partialLoader, $this->resourceManager, $this->debugHandler,
         ['viewPath' => 'views/', 'layoutPath' => 'layouts/']
      );
      $this->cacheManager = new CacheManager($this->layoutRenderer);
      $this->cacheEnabled = $cacheEnabled;
   }

   public function setDebugMode(bool $debugMode) {
      $this->debugHandler->enable($debugMode);
   }

   public function setCacheEnabled(bool $enabled) {
      $this->cacheEnabled = $enabled;
   }

   public function render(string $view, array $data = []): string {
      try {
         if ($this->cacheEnabled && $this->cacheManager->isCached($view, $data)) {
            return $this->cacheManager->loadFromCache($view, $data);
         } else {
            $content = $this->layoutRenderer->render($view, $data);
            if ($this->cacheEnabled) {
               $this->cacheManager->saveToCache($view, $content, $data);
            }
            return $content;
         }
      } catch (FileNotFoundException $e) {
         if ($this->debugHandler->isEnabled()) {
            $this->debugHandler->debug($e->getMessage(), 'Rendering Error');
         }
         throw $e;
      }
   }

   public function renderPartial(string $view, array $data = []): string {
      return $this->layoutRenderer->renderPartial($view, $data);
   }

   public function configure(array $settings) {
      if (isset($settings['cachePath'])) {
         $this->cacheManager->setCachePath($settings['cachePath']);
      }
      if (isset($settings['layoutPath'])) {
         $this->layoutRenderer->setLayoutPath($settings['layoutPath']);
      }
   }
}