<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        ResourceManager
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:19:35
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

class ResourceManager {
   private array $scripts = [];
   private array $styles = [];
   private array $resourcePaths = ['assets/'];
   const DS = DIRECTORY_SEPARATOR;

   public function addResourcePath(string $path): void {
      $this->resourcePaths[] = rtrim($path, self::DS) . self::DS;
   }

   private function findResource(string $filename): string|null {
      foreach ($this->resourcePaths as $path) {
         $fullPath = $path . $filename;
         if (file_exists($fullPath)) {
            return $fullPath;
         }
      }
      return null;
   }

   public function addScript(string $src, array $dependencies = [], bool $inline = false): void {
      if (!$this->isResourceAdded($this->scripts, $src)) {
         $this->scripts[] = ['src' => $src, 'deps' => $dependencies, 'inline' => $inline];
      }
   }

   public function addStyle(string $href, array $dependencies = [], bool $inline = false) {
      if (!$this->isResourceAdded($this->styles, $href)) {
         $this->styles[] = ['href' => $href, 'deps' => $dependencies, 'inline' => $inline];
      }
   }

   private function isResourceAdded(array $resourceList, string $resource) {
      foreach ($resourceList as $item) {
         if ($item['src'] === $resource || $item['href'] === $resource) {
            return true;
         }
      }
      return false;
   }

   public function renderScripts(): string {
      $output = '';
      foreach ($this->scripts as $script) {
         if ($script['inline']) {
            $output .= "<script>" . htmlspecialchars($script['src']) . "</script>\n";
         } else {
            $path = $this->findResource($script['src']);
            if ($path !== null) {
               $output .= "<script src=\"$path\"></script>\n";
            } else {
               throw new \RuntimeException("Script no encontrado: " . $script['src']);
            }
         }
      }
      return $output;
   }

   public function renderStyles(): string {
      $output = '';
      foreach ($this->styles as $style) {
         if ($style['inline']) {
            $output .= "<style>" . htmlspecialchars($style['href']) . "</style>\n";
         } else {
            $path = $this->findResource($style['href']);
            if ($path !== null) {
               $output .= "<link rel=\"stylesheet\" href=\"$path\">\n";
            } else {
               throw new \RuntimeException("Hoja de estilo no encontrada: " . $style['href']);
            }
         }
      }
      return $output;
   }
}