<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        TimeHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:17:22
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;
use \InvalidArgumentException;
class TimeHelper {
   /**
    * Devuelve la hora actual en un formato específico.
    *
    * @param string $format Formato de la hora (por defecto 'H:i:s').
    * @return string Hora actual en el formato especificado.
    */
   public static function getCurrentTime(string $format = 'H:i:s'): string {
      return date($format);
   }

   /**
    * Convierte una marca de tiempo UNIX en un formato legible.
    *
    * @param int $timestamp Marca de tiempo UNIX.
    * @param string $format Formato deseado (por defecto 'Y-m-d H:i:s').
    * @return string Fecha y hora formateadas.
    */
   public static function formatTimestamp(int $timestamp, string $format = 'Y-m-d H:i:s'): string {
      return date($format, $timestamp);
   }

   /**
    * Calcula la diferencia entre dos horas y devuelve el resultado en un formato legible.
    *
    * @param string $startTime Hora de inicio (formato 'H:i:s').
    * @param string $endTime Hora de fin (formato 'H:i:s').
    * @return string Diferencia entre las dos horas en formato "H:i:s".
    */
   public static function calculateTimeDifference(string $startTime, string $endTime): string {
      $start = strtotime($startTime);
      $end = strtotime($endTime);

      if ($end < $start) {
         throw new InvalidArgumentException("La hora de fin no puede ser anterior a la de inicio.");
      }

      $difference = $end - $start;
      return gmdate('H:i:s', $difference);
   }

   /**
    * Convierte un tiempo en segundos a un formato "H:i:s".
    *
    * @param int $seconds Tiempo en segundos.
    * @return string Tiempo formateado como "H:i:s".
    */
   public static function secondsToTime(int $seconds): string {
      return gmdate('H:i:s', $seconds);
   }

   /**
    * Convierte un formato "H:i:s" a segundos totales.
    *
    * @param string $time Tiempo en formato "H:i:s".
    * @return int Tiempo en segundos.
    */
   public static function timeToSeconds(string $time): int {
      $parts = explode(':', $time);
      if (count($parts) !== 3) {
         throw new InvalidArgumentException("El formato de tiempo debe ser 'H:i:s'.");
      }

      [$hours, $minutes, $seconds] = array_map('intval', $parts);
      return ($hours * 3600) + ($minutes * 60) + $seconds;
   }

   /**
    * Agrega un intervalo de tiempo a una hora específica.
    *
    * @param string $time Hora inicial en formato "H:i:s".
    * @param int $seconds Cantidad de segundos a agregar.
    * @return string Nueva hora en formato "H:i:s".
    */
   public static function addSecondsToTime(string $time, int $seconds): string {
      $timeInSeconds = self::timeToSeconds($time) + $seconds;
      return self::secondsToTime($timeInSeconds);
   }

   /**
    * Comprueba si una hora específica está dentro de un rango.
    *
    * @param string $time Hora a comprobar (formato 'H:i:s').
    * @param string $startTime Inicio del rango (formato 'H:i:s').
    * @param string $endTime Fin del rango (formato 'H:i:s').
    * @return bool True si está dentro del rango, false en caso contrario.
    */
   public static function isTimeInRange(string $time, string $startTime, string $endTime): bool {
      $timeSeconds = self::timeToSeconds($time);
      $startSeconds = self::timeToSeconds($startTime);
      $endSeconds = self::timeToSeconds($endTime);

      return $timeSeconds >= $startSeconds && $timeSeconds <= $endSeconds;
   }
}