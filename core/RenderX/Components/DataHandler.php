<?php
/**
 * @package     core/RenderX
 * @subpackage  Components
 * @file        DataHandler
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-18 09:14:49
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Components;
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