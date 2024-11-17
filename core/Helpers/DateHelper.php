<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        DateHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:24:54
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;
use \DateTime;
class DateHelper {
   /**
    * Formatea una fecha a un formato específico.
    *
    * @param string $date La fecha a formatear.
    * @param string $format El formato deseado (por defecto 'Y-m-d H:i:s').
    * @return string La fecha formateada.
    */
   public static function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string {
      $datetime = new DateTime($date);
      return $datetime->format($format);
   }

   /**
    * Convierte una fecha en un formato "tiempo transcurrido".
    * Ejemplo: "hace 2 horas", "hace 3 días".
    *
    * @param string $datetime La fecha en formato datetime.
    * @return string El tiempo transcurrido.
    */
   public static function timeAgo(string $datetime): string {
      $time_ago = strtotime($datetime);
      $current_time = time();
      $time_difference = $current_time - $time_ago;
      $seconds = $time_difference;
      $minutes = round($seconds / 60);           // value 60 is seconds
      $hours = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
      $days = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
      $weeks = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
      $months = round($seconds / 2629440);      // value 2629440 is (365.25 days * 24 hours * 60 minutes * 60 sec) / 12 months
      $years = round($seconds / 31553280);     // value 31553280 is (365.25 days * 24 hours * 60 minutes * 60 sec)

      if ($seconds <= 60) {
         return "Hace menos de un minuto";
      } else if ($minutes <= 60) {
         return ($minutes == 1) ? "Hace un minuto" : "Hace $minutes minutos";
      } else if ($hours <= 24) {
         return ($hours == 1) ? "Hace una hora" : "Hace $hours horas";
      } else if ($days <= 7) {
         return ($days == 1) ? "Ayer" : "Hace $days días";
      } else if ($weeks <= 4.3) { // 4.3 == 30/7
         return ($weeks == 1) ? "Hace una semana" : "Hace $weeks semanas";
      } else if ($months <= 12) {
         return ($months == 1) ? "Hace un mes" : "Hace $months meses";
      } else {
         return ($years == 1) ? "Hace un año" : "Hace $years años";
      }
   }

   /**
    * Agrega una cantidad de días a una fecha.
    *
    * @param string $date La fecha de inicio.
    * @param int $days El número de días a agregar.
    * @return string La nueva fecha con los días agregados.
    */
   public static function addDays(string $date, int $days): string {
      $datetime = new DateTime($date);
      $datetime->modify("+$days days");
      return $datetime->format('Y-m-d H:i:s');
   }

   /**
    * Devuelve la fecha y hora actual en un formato estándar.
    *
    * @return string La fecha y hora actual en formato 'Y-m-d H:i:s'.
    */
   public static function getCurrentDate(): string {
      $datetime = new DateTime();
      return $datetime->format('Y-m-d H:i:s');
   }

   /**
    * Verifica si la fecha dada es un fin de semana (sábado o domingo).
    *
    * @param string $date La fecha a verificar.
    * @return bool True si es fin de semana, false si no lo es.
    */
   public static function isWeekend(string $date): bool {
      $datetime = new DateTime($date);
      $dayOfWeek = $datetime->format('N'); // 1 (lunes) a 7 (domingo)
      return in_array($dayOfWeek, [6, 7]); // 6 = sábado, 7 = domingo
   }

   /**
    * Obtiene el primer día del mes de una fecha dada.
    *
    * @param string $date La fecha a partir de la cual obtener el primer día del mes.
    * @return string La fecha del primer día del mes.
    */
   public static function firstDayOfMonth(string $date): string {
      $datetime = new DateTime($date);
      $datetime->modify('first day of this month');
      return $datetime->format('Y-m-d');
   }

   /**
    * Obtiene el último día del mes de una fecha dada.
    *
    * @param string $date La fecha a partir de la cual obtener el último día del mes.
    * @return string La fecha del último día del mes.
    */
   public static function lastDayOfMonth(string $date): string {
      $datetime = new DateTime($date);
      $datetime->modify('last day of this month');
      return $datetime->format('Y-m-d');
   }

   /**
    * Compara dos fechas y devuelve la diferencia en días.
    *
    * @param string $startDate La fecha de inicio.
    * @param string $endDate La fecha final.
    * @return int La diferencia en días.
    */
   public static function dateDifference(string $startDate, string $endDate): int {
      $start = new DateTime($startDate);
      $end = new DateTime($endDate);
      $interval = $start->diff($end);
      return $interval->days;
   }

   /**
    * Verifica si una fecha es válida.
    *
    * @param string $date La fecha a verificar.
    * @return bool True si la fecha es válida, false si no lo es.
    */
   public static function isValidDate(string $date): bool {
      $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
      return $datetime && $datetime->format('Y-m-d H:i:s') === $date;
   }
}