<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        ArrayHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:23:52
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

class ArrayHelper {
   /**
    * Aplana un array multidimensional.
    * Convierte un array multidimensional en un array unidimensional.
    *
    * @param array $array
    * @return array
    */
   public static function flatten(array $array): array {
      $result = [];
      array_walk_recursive($array, function ($value) use (&$result) {
         $result[] = $value;
      });
      return $result;
   }

   /**
    * Realiza un merge profundo de dos arrays.
    * Los valores de las claves coincidentes del segundo array sobrescribirán los del primero.
    *
    * @param array $array1
    * @param array $array2
    * @return array
    */
   public static function deepMerge(array $array1, array $array2): array {
      foreach ($array2 as $key => $value) {
         if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
            $array1[$key] = self::deepMerge($array1[$key], $value); // Merge recursivo
         } else {
            $array1[$key] = $value;
         }
      }
      return $array1;
   }

   /**
    * Busca un valor dentro de un array y devuelve la clave si existe.
    * Devuelve la clave correspondiente al valor buscado o null si no se encuentra.
    *
    * @param array $array
    * @param mixed $value
    * @return mixed|null
    */
   public static function arraySearch(array $array, $value) {
      $key = array_search($value, $array);
      return $key !== false ? $key : null;
   }

   /**
    * Ordena un array multidimensional por una clave específica.
    * Permite ordenar en orden ascendente o descendente.
    *
    * @param array $array
    * @param string $key
    * @param bool $desc
    * @return array
    */
   public static function arraySort(array $array, string $key, bool $desc = false): array {
      usort($array, function ($a, $b) use ($key, $desc) {
         if ($a[$key] == $b[$key]) {
            return 0;
         }
         return ($desc ? $a[$key] < $b[$key] : $a[$key] > $b[$key]) ? 1 : -1;
      });
      return $array;
   }

   /**
    * Extrae una columna o propiedad de un array multidimensional.
    * Extrae los valores correspondientes a una clave o propiedad de un array multidimensional.
    *
    * @param array $array
    * @param string $key
    * @return array
    */
   public static function pluck(array $array, string $key): array {
      return array_map(function ($item) use ($key) {
         return $item[$key] ?? null;
      }, $array);
   }

   /**
    * Filtra un array y devuelve solo aquellos elementos que cumplan con el criterio de una función de callback.
    *
    * @param array $array
    * @param callable $callback
    * @return array
    */
   public static function filter(array $array, callable $callback): array {
      return array_filter($array, $callback);
   }

   /**
    * Devuelve el primer valor de un array o el valor predeterminado si está vacío.
    *
    * @param array $array
    * @param mixed $default
    * @return mixed
    */
   public static function first(array $array, $default = null) {
      return !empty($array) ? reset($array) : $default;
   }

   /**
    * Devuelve el último valor de un array o el valor predeterminado si está vacío.
    *
    * @param array $array
    * @param mixed $default
    * @return mixed
    */
   public static function last(array $array, $default = null) {
      return !empty($array) ? end($array) : $default;
   }

   /**
    * Convierte un array en un objeto stdClass.
    *
    * @param array $array
    * @return object
    */
   public static function toObject(array $array): object {
      return (object) $array;
   }

   /**
    * Determina si un array está vacío (sin elementos).
    *
    * @param array $array
    * @return bool
    */
   public static function isEmpty(array $array): bool {
      return empty($array);
   }

   /**
    * Agrupa un array multidimensional según una clave específica.
    *
    * @param array $array
    * @param string $key
    * @return array
    */
   public static function groupBy(array $array, string $key): array {
      $result = [];
      foreach ($array as $item) {
         if (isset($item[$key])) {
            $result[$item[$key]][] = $item;
         }
      }
      return $result;
   }
}