<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        DataHandlerInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:06
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface DataHandlerInterface {
   /**
    * Establece los datos para la vista.
    *
    * @param array $data Datos a establecer.
    * @param bool $overwrite Si es true, sobreescribe los datos existentes; de lo contrario, se concatenan.
    * @return void
    * @throws \InvalidArgumentException Si los datos no son un array.
    */
   public function setData(array $data, bool $overwrite = true): void;

   /**
    * Obtiene los datos para la vista.
    *
    * @param string|null $key Clave del dato a obtener. Si es null, devuelve todos los datos.
    * @param mixed $default Valor por defecto si la clave no existe.
    * @return mixed Los datos solicitados o el valor por defecto.
    */
   public function getData(string $key = null, mixed $default = null): mixed;

   /**
    * Prepara los datos para la vista, realizando las transformaciones necesarias.  Por ejemplo,
    * extraer variables para usar en las vistas.
    * @return array
    */
   public function prepareDataForView(): array;

   /**
    * Agrega una miga de pan al array de migas de pan.
    * @param string $label Etiqueta de la miga de pan.
    * @param string|null $url URL de la miga de pan (opcional).
    * @return void
    * @throws \InvalidArgumentException Si la etiqueta está vacía o la URL no es válida.
    */
   public function setBreadcrumb(string $label, ?string $url = null): void;

   /**
    * Obtiene el array de migas de pan.
    * @return array
    */
   public function getBreadcrumbs(): array;

}