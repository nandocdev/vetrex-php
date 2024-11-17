<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        ValidatorHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:06:47
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

class ValidatorHelper {
   /**
    * Valida si una cadena es un email válido.
    *
    * @param string $email Email a validar.
    * @return bool True si es válido, false en caso contrario.
    */
   public static function isEmailValid(string $email): bool {
      return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
   }

   /**
    * Valida si una cadena es un número de teléfono válido.
    *
    * @param string $phone Número de teléfono a validar.
    * @return bool True si es válido, false en caso contrario.
    */
   public static function isPhoneNumberValid(string $phone): bool {
      // Validación básica: solo números, espacios, guiones, paréntesis, y '+' para internacional
      return preg_match('/^\+?[0-9\s\-\(\)]+$/', $phone) === 1;
   }

   /**
    * Valida si una URL es válida.
    *
    * @param string $url URL a validar.
    * @return bool True si es válida, false en caso contrario.
    */
   public static function isUrlValid(string $url): bool {
      return filter_var($url, FILTER_VALIDATE_URL) !== false;
   }

   /**
    * Verifica si una contraseña es lo suficientemente fuerte.
    *
    * @param string $password Contraseña a validar.
    * @return bool True si es fuerte, false en caso contrario.
    */
   public static function isStrongPassword(string $password): bool {
      // Debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y caracteres especiales
      return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password) === 1;
   }

   /**
    * Valida si una cadena tiene una longitud mínima y máxima.
    *
    * @param string $string Cadena a validar.
    * @param int $min Longitud mínima.
    * @param int $max Longitud máxima.
    * @return bool True si cumple con las longitudes, false en caso contrario.
    */
   public static function isLengthValid(string $string, int $min = 0, int $max = PHP_INT_MAX): bool {
      $length = strlen($string);
      return $length >= $min && $length <= $max;
   }

   /**
    * Valida si una cadena contiene solo caracteres alfanuméricos.
    *
    * @param string $string Cadena a validar.
    * @return bool True si es alfanumérica, false en caso contrario.
    */
   public static function isAlphanumeric(string $string): bool {
      return ctype_alnum($string);
   }

   /**
    * Valida si una cadena está compuesta únicamente por dígitos.
    *
    * @param string $string Cadena a validar.
    * @return bool True si son solo dígitos, false en caso contrario.
    */
   public static function isNumeric(string $string): bool {
      return ctype_digit($string);
   }
}