<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        DataHandler.php
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 20:21:53
 * @version     1.0.0
 * @description Clase encargada de administrar los datos pasados a las vistas, 
 *              proporcionando métodos seguros y eficientes para su manipulación 
 *              y extracción en el proceso de renderizado.
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;
use InvalidArgumentException;
use Vertex\Core\RenderX\Interfaces\DataHandlerInterface;
class DataHandler implements DataHandlerInterface {
   private array $data = [];
   private array $breadcrumbs = [];

   /**
    * Establece los datos para la vista.
    *
    * @param array $data Datos a establecer.
    * @param bool $overwrite Si es true, sobreescribe los datos existentes; de lo contrario, se combinan.
    * @return void
    */
   public function setData(array $data, bool $overwrite = true): void {
      $this->data = $overwrite ? array_merge($this->data, $data) : array_merge($data, $this->data);
   }

   /**
    * Obtiene los datos para la vista.
    *
    * @param string|null $key Clave del dato a obtener. Si es null, devuelve todos los datos.
    * @param mixed $default Valor por defecto si la clave no existe.
    * @return mixed Los datos solicitados o el valor por defecto.
    */
   public function getData(string $key = null, mixed $default = null): mixed {
      if ($key === null) {
         return $this->data;
      }

      return $this->data[$key] ?? $default;
   }

   /**
    * Prepara los datos para la vista, retornando un array de datos procesados.
    * Este método no modifica el estado interno.
    *
    * @return array Datos listos para la vista.
    */
   public function prepareDataForView(): array {
      return $this->data;
   }

   /**
    * Limpia todos los datos almacenados.
    *
    * @return void
    */
   public function clearData(): void {
      $this->data = [];
   }

   /**
    * Agrega una miga de pan al array de migas de pan.
    *
    * @param string $label Etiqueta de la miga de pan.
    * @param string|null $url URL de la miga de pan (opcional). Acepta rutas relativas y absolutas.
    * @return void
    */
   public function setBreadcrumb(string $label, ?string $url = null): void {
      if (empty($label)) {
         throw new InvalidArgumentException("El label debe ser una cadena no vacía.");
      }

      if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL) && !preg_match('/^(\/|\.\/|\.\.\/)/', $url)) {
         throw new InvalidArgumentException("El URL proporcionado no es válido.");
      }

      $this->breadcrumbs[] = ['label' => $label, 'url' => $url];
   }

   /**
    * Obtiene el array de migas de pan.
    *
    * @return array
    */
   public function getBreadcrumbs(): array {
      return $this->breadcrumbs;
   }

   /**
    * Elimina todas las migas de pan.
    *
    * @return void
    */
   public function clearBreadcrumbs(): void {
      $this->breadcrumbs = [];
   }

   /**
    * Elimina una miga de pan por su índice.
    *
    * @param int $index Índice de la miga de pan a eliminar.
    * @return void
    * @throws InvalidArgumentException Si el índice es inválido.
    */
   public function removeBreadcrumb(int $index): void {
      if (!isset($this->breadcrumbs[$index])) {
         throw new InvalidArgumentException("El índice proporcionado no existe.");
      }

      unset($this->breadcrumbs[$index]);
      $this->breadcrumbs = array_values($this->breadcrumbs); // Reindexa el array.
   }
}