<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        ArrayFlattern
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:17:04
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

class ArrayFlattern {
   /**
    * Aplana completamente un array multidimensional.
    *
    * @param array $array Array multidimensional.
    * @return array Array aplanado.
    */
   public static function flattenDeep(array $array): array {
      $result = [];
      array_walk_recursive($array, function ($value) use (&$result) {
         $result[] = $value;
      });
      return $result;
   }

   /**
    * Agrupa los elementos de un array por una clave específica.
    *
    * @param array $array Array de entrada.
    * @param string $key Clave por la cual agrupar.
    * @return array Array agrupado por la clave.
    */
   public static function groupBy(array $array, string $key): array {
      $grouped = [];
      foreach ($array as $element) {
         if (isset($element[$key])) {
            $grouped[$element[$key]][] = $element;
         }
      }
      return $grouped;
   }

   /**
    * Convierte un array asociativo en un array plano de pares clave-valor.
    *
    * @param array $array Array asociativo.
    * @return array Array de pares clave-valor.
    */
   public static function flattenToPairs(array $array): array {
      $result = [];
      foreach ($array as $key => $value) {
         $result[] = ['key' => $key, 'value' => $value];
      }
      return $result;
   }

   /**
    * Aplana un array manteniendo las claves.
    *
    * @param array $array Array multidimensional.
    * @param string $separator Separador entre claves.
    * @return array Array aplanado con claves concatenadas.
    */
   public static function flattenWithKeys(array $array, string $separator = '.'): array {
      $result = [];
      $flatten = function ($items, $prefix = '') use (&$result, &$flatten, $separator) {
         foreach ($items as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . $separator . $key;
            if (is_array($value)) {
               $flatten($value, $newKey);
            } else {
               $result[$newKey] = $value;
            }
         }
      };
      $flatten($array);
      return $result;
   }

   /**
    * Filtra un array y devuelve los elementos que tienen una clave específica.
    *
    * @param array $array Array de entrada.
    * @param string $key Clave a buscar.
    * @return array Elementos que contienen la clave.
    */
   public static function filterByKey(array $array, string $key): array {
      return array_filter($array, fn($element) => isset ($element[$key]));
   }
}