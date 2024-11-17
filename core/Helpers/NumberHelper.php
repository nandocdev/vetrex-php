<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        NumberHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:01:47
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \Exception;

class NumberHelper {
   /**
    * Formatea un número como una cantidad de dinero.
    *
    * @param float $amount El monto a formatear.
    * @param string $currency El código de la moneda (por defecto 'USD').
    * @return string El monto formateado como moneda.
    */
   public static function formatCurrency(float $amount, string $currency = 'USD'): string {
      $symbol = match ($currency) {
         'USD' => '$',
         'EUR' => '€',
         'GBP' => '£',
         default => $currency . ' ',
      };
      return $symbol . number_format($amount, 2, '.', ',');
   }

   /**
    * Redondea un número a un número específico de decimales.
    *
    * @param float $value El número a redondear.
    * @param int $precision El número de decimales (por defecto 2).
    * @return float El número redondeado.
    */
   public static function roundToPrecision(float $value, int $precision = 2): float {
      return round($value, $precision);
   }

   /**
    * Convierte un tamaño en formato legible (ej. "10MB") a bytes.
    *
    * @param string $size El tamaño en formato legible (ej. "10MB", "5GB").
    * @return int El tamaño en bytes.
    * @throws Exception Si el formato del tamaño no es válido.
    */
   public static function convertToBytes(string $size): int {
      $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
      $size = strtoupper(trim($size));
      $unit = preg_replace('/[^A-Z]/', '', $size);
      $value = (int) preg_replace('/[^0-9]/', '', $size);

      $exponent = array_search($unit, $units, true);

      if ($exponent === false) {
         throw new Exception("Formato de tamaño no válido: $size");
      }

      return $value * (1024 ** $exponent);
   }

   /**
    * Genera un número aleatorio de una longitud específica.
    *
    * @param int $length La longitud del número aleatorio.
    * @return string El número aleatorio como string.
    * @throws Exception Si la longitud es menor o igual a cero.
    */
   public static function generateRandomNumber(int $length): string {
      if ($length <= 0) {
         throw new Exception("La longitud debe ser mayor que cero.");
      }
      return substr(str_repeat('0123456789', $length), 0, $length);
   }

   /**
    * Calcula el porcentaje de un número.
    *
    * @param float $total El número total.
    * @param float $value El valor del cual se calculará el porcentaje.
    * @return float El porcentaje calculado.
    */
   public static function calculatePercentage(float $total, float $value): float {
      if ($total === 0.0) {
         return 0.0;
      }
      return ($value / $total) * 100;
   }

   /**
    * Convierte un número en formato legible (ej. 1048576 a "1 MB").
    *
    * @param int $bytes El tamaño en bytes.
    * @return string El tamaño en formato legible.
    */
   public static function bytesToReadable(int $bytes): string {
      $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
      $factor = floor((strlen((string) $bytes) - 1) / 3);
      return sprintf("%.2f %s", $bytes / (1024 ** $factor), $units[$factor]);
   }
}