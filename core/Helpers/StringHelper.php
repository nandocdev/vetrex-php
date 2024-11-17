<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        StringHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:19:13
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;



class StringHelper {
   /**
    * Capitaliza la primera letra de cada palabra en una cadena.
    *
    * @param string $str
    * @return string
    */
   public static function capitalizeWords(string $str): string {
      return ucwords(strtolower($str));
   }

   /**
    * Convierte una cadena a formato slug (por ejemplo, "Hello World!" -> "hello-world").
    *
    * @param string $str
    * @return string
    */
   public static function slugify(string $str): string {
      // Convierte la cadena a minúsculas, reemplaza caracteres no alfanuméricos con guiones y elimina guiones duplicados.
      return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($str)));
   }

   /**
    * Trunca una cadena a una longitud específica, agregando "..." al final si es necesario.
    *
    * @param string $str
    * @param int $length
    * @param string $suffix
    * @return string
    */
   public static function truncate(string $str, int $length, string $suffix = '...'): string {
      if (strlen($str) <= $length) {
         return $str;
      }

      return substr($str, 0, $length) . $suffix;
   }

   /**
    * Verifica si una cadena contiene otra.
    *
    * @param string $haystack
    * @param string $needle
    * @return bool
    */
   public static function contains(string $haystack, string $needle): bool {
      return strpos($haystack, $needle) !== false;
   }

   /**
    * Escapa caracteres especiales en HTML para prevenir inyecciones.
    *
    * @param string $str
    * @return string
    */
   public static function escapeHtml(string $str): string {
      return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
   }

   /**
    * Elimina todos los espacios en blanco al principio y al final de la cadena.
    *
    * @param string $str
    * @return string
    */
   public static function trimSpaces(string $str): string {
      return trim($str);
   }

   /**
    * Reemplaza todos los espacios múltiples con un solo espacio.
    *
    * @param string $str
    * @return string
    */
   public static function normalizeSpaces(string $str): string {
      return preg_replace('/\s+/', ' ', $str);
   }

   /**
    * Genera un hash MD5 de una cadena, útil para la creación de identificadores únicos.
    *
    * @param string $str
    * @return string
    */
   public static function generateMd5Hash(string $str): string {
      return md5($str);
   }

   /**
    * Verifica si una cadena está vacía.
    *
    * @param string $str
    * @return bool
    */
   public static function isEmpty(string $str): bool {
      return empty($str);
   }

   /**
    * Convierte una cadena en minúsculas.
    *
    * @param string $str
    * @return string
    */
   public static function toLowerCase(string $str): string {
      return strtolower($str);
   }

   /**
    * Convierte una cadena en mayúsculas.
    *
    * @param string $str
    * @return string
    */
   public static function toUpperCase(string $str): string {
      return strtoupper($str);
   }

   /**
    * Reemplaza una cadena por otra en un texto.
    *
    * @param string $str
    * @param string $search
    * @param string $replace
    * @return string
    */
   public static function replace(string $str, string $search, string $replace): string {
      return str_replace($search, $replace, $str);
   }

   /**
    * Verifica si la cadena empieza con un prefijo dado.
    *
    * @param string $str
    * @param string $prefix
    * @return bool
    */
   public static function startsWith(string $str, string $prefix): bool {
      return substr($str, 0, strlen($prefix)) === $prefix;
   }

   /**
    * Verifica si la cadena termina con un sufijo dado.
    *
    * @param string $str
    * @param string $suffix
    * @return bool
    */
   public static function endsWith(string $str, string $suffix): bool {
      return substr($str, -strlen($suffix)) === $suffix;
   }

   /**
    * Cuenta las ocurrencias de una subcadena dentro de una cadena.
    *
    * @param string $str
    * @param string $substring
    * @return int
    */
   public static function countOccurrences(string $str, string $substring): int {
      return substr_count($str, $substring);
   }

   /**
    * Convierte una cadena en formato de URL amigable (ej. "First Post!" a "first-post").
    *
    * @param string $str
    * @return string
    */
   public static function toUrlFriendly(string $str): string {
      return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($str)));
   }
}