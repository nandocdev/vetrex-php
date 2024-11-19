<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        DataHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:12:57
 * @version     1.0.0
 * @description: Manejador de datos para las vistas.
 */
declare(strict_types=1);

namespace Vertex\Core\View;

use InvalidArgumentException;

class DataHandler {
   private array $data = [];
   private array $breadcrumbs = [];

   public function setData(array $data, bool $overwrite = true): void {
      if (!is_array($data)) {
         throw new InvalidArgumentException("Los datos deben ser un array.");
      }
      $this->data = $overwrite ? array_merge($this->data, $data) : array_merge($data, $this->data);
   }

   public function getData(string $key = null, mixed $default = null): mixed {
      if ($key === null) {
         return $this->data;
      }
      return $this->data[$key] ?? $default;
   }

   public function setBreadcrumb(string $label, ?string $url = null): void {
      if (!is_string($label) || empty($label)) {
         throw new InvalidArgumentException("El label debe ser una cadena no vacía.");
      }
      if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL)) {
         throw new InvalidArgumentException("El URL proporcionado no es válido.");
      }
      $this->breadcrumbs[] = ['label' => $label, 'url' => $url];
   }

   public function getBreadcrumbs(): array {
      return $this->breadcrumbs;
   }

   public function prepareDataForView(): array {
      $data = $this->data;

      // Si se requiere limitar la exposición de datos:
      // unset($data['clave_sensible']);
      extract($data);
      return get_defined_vars();
   }
}