<?php
/**
 * @package     vertex/core
 * @subpackage  Plugins
 * @file        FileSystem
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 08:57:18
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Plugins;

class FileSystem {
   public string $path;

   public function __construct(string $path) {
      $this->path = $path;
   }

   public function getPath(): string {
      return $this->path;
   }

   public function setPath(string $path): void {
      $this->path = $path;
   }

   public function exists(): bool {
      return file_exists($this->path);
   }

   public function read(): string {
      if (!$this->exists()) {
         throw new \Exception("El archivo no existe: {$this->path}");
      }
      return file_get_contents($this->path);
   }

   public function delete(): bool {
      if (!$this->exists()) {
         throw new \Exception("El archivo no existe: {$this->path}");
      }
      return unlink($this->path);
   }

   public function write(string $content): bool {
      return file_put_contents($this->path, $content) !== false;
   }

   public function append(string $content): bool {
      return file_put_contents($this->path, $content, FILE_APPEND) !== false;
   }

   public function copy(string $destination): bool {
      return copy($this->path, $destination);
   }

   public function move(string $destination): bool {
      return rename($this->path, $destination);
   }

   public function getExtension(): string {
      return pathinfo($this->path, PATHINFO_EXTENSION);
   }

   public function getFilename(): string {
      return pathinfo($this->path, PATHINFO_FILENAME);
   }

   public function getBasename(): string {
      return pathinfo($this->path, PATHINFO_BASENAME);
   }

   public function getDirname(): string {
      return pathinfo($this->path, PATHINFO_DIRNAME);
   }

   public function getSize(): int {
      return filesize($this->path);
   }

   public function getMimeType(): string {
      return mime_content_type($this->path);
   }

   public function getOwner(): int {
      return fileowner($this->path);
   }

   public function getGroup(): int {
      return filegroup($this->path);
   }
}