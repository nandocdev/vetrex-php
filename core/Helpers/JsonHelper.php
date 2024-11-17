<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        JsonHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:56:43
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \Exception;
class JsonHelper {
   /**
    * Decodifica un string JSON a un array asociativo.
    *
    * @param string $json El string JSON a decodificar.
    * @return array El array decodificado.
    * @throws Exception Si el string JSON no es válido.
    */
   public static function decode(string $json): array {
      $decoded = json_decode($json, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
         throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
      }
      return $decoded;
   }

   /**
    * Codifica un array a un string JSON.
    *
    * @param array $data El array a codificar.
    * @return string El string JSON codificado.
    * @throws Exception Si no se puede codificar el array.
    */
   public static function encode(array $data): string {
      $encoded = json_encode($data, JSON_UNESCAPED_UNICODE);
      if (json_last_error() !== JSON_ERROR_NONE) {
         throw new Exception("Error al codificar JSON: " . json_last_error_msg());
      }
      return $encoded;
   }

   /**
    * Devuelve un string JSON con formato legible para el ser humano.
    *
    * @param string $json El string JSON original.
    * @return string El string JSON con formato legible.
    * @throws Exception Si el string JSON no es válido.
    */
   public static function prettyPrint(string $json): string {
      $decoded = self::decode($json); // Asegurarse de que el JSON sea válido
      return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
   }

   /**
    * Verifica si un string es un JSON válido.
    *
    * @param string $json El string a verificar.
    * @return bool True si es un JSON válido, false si no.
    */
   public static function isValidJson(string $json): bool {
      json_decode($json);
      return json_last_error() === JSON_ERROR_NONE;
   }

   /**
    * Busca un valor dentro de un JSON y devuelve las claves asociadas.
    *
    * @param string $json El string JSON donde buscar.
    * @param mixed $value El valor a buscar.
    * @return array Las claves asociadas al valor encontrado.
    * @throws Exception Si el string JSON no es válido.
    */
   public static function searchValue(string $json, $value): array {
      $decoded = self::decode($json); // Decodificar el JSON
      $keys = [];

      array_walk_recursive($decoded, function ($item, $key) use ($value, &$keys) {
         if ($item === $value) {
            $keys[] = $key;
         }
      });

      return $keys;
   }

   /**
    * Combina dos strings JSON en uno solo.
    *
    * @param string $json1 El primer string JSON.
    * @param string $json2 El segundo string JSON.
    * @return string El string JSON combinado.
    * @throws Exception Si alguno de los strings JSON no es válido.
    */
   public static function merge(string $json1, string $json2): string {
      $array1 = self::decode($json1);
      $array2 = self::decode($json2);

      $merged = array_merge_recursive($array1, $array2);

      return self::encode($merged);
   }
}