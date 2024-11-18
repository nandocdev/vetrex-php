<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        CacheManagerInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:50
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface CacheManagerInterface {
   /**
    * Establece la ruta de la caché.
    * @param string $path
    * @return void
    */
   public function setCachePath(string $path): void;

   /**
    * Verifica si una vista está en caché.
    *
    * @param string $view Nombre de la vista.
    * @param array|null $data Datos utilizados para generar la clave de caché.
    * @param string|null $layout Nombre del layout utilizado.
    * @return bool True si la vista está en caché, false en caso contrario.
    */
   public function isCached(string $view, ?array $data = null, ?string $layout = null): bool;

   /**
    * Carga el contenido de la caché.
    *
    * @param string $view Nombre de la vista.
    * @param array|null $data Datos utilizados para generar la clave de caché.
    * @param string|null $layout Nombre del layout utilizado.
    * @return string|null El contenido de la caché, o null si no se encuentra.
    */
   public function loadFromCache(string $view, ?array $data = null, ?string $layout = null): ?string;

   /**
    * Guarda el contenido en la caché.
    *
    * @param string $view Nombre de la vista.
    * @param string $content Contenido a guardar.
    * @param array|null $data Datos utilizados para generar la clave de caché.
    * @param string|null $layout Nombre del layout utilizado.
    * @return void
    */
   public function saveToCache(string $view, string $content, ?array $data = null, ?string $layout = null): void;

   /**
    * Renderiza y guarda en caché.
    * @param string $view
    * @param array $data
    * @param string|null $layout
    * @return string
    */
   public function renderAndCache(string $view, array $data = [], ?string $layout = null): string;

   /**
    * Limpia la caché.
    * @param string|null $view
    * @param array|null $data
    * @return void
    */
   public function clearCache(?string $view = null, ?array $data = null): void;
}