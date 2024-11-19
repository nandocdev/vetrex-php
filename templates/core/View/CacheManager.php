<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        CacheManager
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:23:39
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

class CacheManager {
   private LayoutRenderer $layoutRenderer;
   private string $cachePath;
   private string $viewPath;
   private string $cacheExtension = '.html';
   private int $cacheLifetime;

   public function __construct(
      LayoutRenderer $layoutRenderer,
      string $cachePath = 'cache/',
      string $viewPath = 'views/',
      int $cacheLifetime = 3600
   ) {
      $this->layoutRenderer = $layoutRenderer;
      $this->cachePath = $cachePath;
      $this->viewPath = $viewPath;
      $this->cacheLifetime = $cacheLifetime;

      if (!is_dir($this->cachePath)) {
         mkdir($this->cachePath, 0777, true);
      }
   }

   public function setCachePath(string $path) {
      $this->cachePath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
   }

   private function getCacheKey($view, $data = null, $layout = null): string {
      $key = $view;
      if ($layout) {
         $key .= '_' . $layout;
      }
      if ($data) {
         $key .= '_' . md5(serialize($data));
      }
      return $key . $this->cacheExtension;
   }

   public function isCached($view, $data = null, $layout = null): bool {
      $cacheFile = $this->cachePath . $this->getCacheKey($view, $data, $layout);
      return file_exists($cacheFile) &&
         (filemtime($cacheFile) > filemtime($this->viewPath . $view . '.php')) &&
         (time() - filemtime($cacheFile) < $this->cacheLifetime);
   }

   public function loadFromCache($view, $data = null, $layout = null): ?string {
      if ($this->isCached($view, $data, $layout)) {
         return file_get_contents($this->cachePath . $this->getCacheKey($view, $data, $layout));
      }
      return null;
   }

   public function saveToCache($view, $content, $data = null, $layout = null): void {
      $cacheFile = $this->cachePath . $this->getCacheKey($view, $data, $layout);
      if (false === @file_put_contents($cacheFile, $content)) {
         trigger_error("No se pudo guardar el caché en: $cacheFile", E_USER_WARNING);
      }
   }

   public function renderAndCache($view, array $data = [], $layout = null): string {
      if ($this->isCached($view, $data, $layout)) {
         return $this->loadFromCache($view, $data, $layout);
      } else {
         $content = $this->layoutRenderer->render($view, $data);
         $this->saveToCache($view, $content, $data, $layout);
         return $content;
      }
   }

   public function clearCache($view = null, $data = null): void {
      if ($view) {
         $cacheFile = $this->cachePath . $this->getCacheKey($view, $data);
         if (file_exists($cacheFile)) {
            unlink($cacheFile);
         }
      } else {
         array_map('unlink', glob($this->cachePath . '*' . $this->cacheExtension));
      }
   }
}