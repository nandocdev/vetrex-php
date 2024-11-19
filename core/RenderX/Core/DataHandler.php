<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        DataHandler
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-19 11:26:52
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

use InvalidArgumentException;

class DataHandler {
   private array $data = [];
   private array $breadcrumbs = [];


   public function setData(array $data, bool $override = false): void {
      $this->data = $override ? $data : array_merge($this->data, $data);
   }


   public function getData(string $key, mixed $default = null) {
      return $this->data[$key] ?? $default;
   }

   public function removeData(string $key): void {
      unset($this->data[$key]);
   }

   public function prepareDatForView(): array {
      $data = $this->data;
      return $data;
   }
}