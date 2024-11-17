<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        SessionHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:11:15
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

class SessionHelper {
   /**
    * Inicia la sesión si aún no está iniciada.
    */
   public static function startSession(): void {
      if (session_status() !== PHP_SESSION_ACTIVE) {
         session_start();
      }
   }

   /**
    * Obtiene un valor de la sesión.
    *
    * @param string $key Clave del valor a obtener.
    * @return mixed Valor almacenado en la sesión o null si no existe.
    */
   public static function getSession(string $key): mixed {
      self::startSession();
      return $_SESSION[$key] ?? null;
   }

   /**
    * Establece un valor en la sesión.
    *
    * @param string $key Clave del valor a establecer.
    * @param mixed $value Valor a almacenar en la sesión.
    * @return void
    */
   public static function setSession(string $key, mixed $value): void {
      self::startSession();
      $_SESSION[$key] = $value;
   }

   /**
    * Elimina un valor de la sesión.
    *
    * @param string $key Clave del valor a eliminar.
    * @return void
    */
   public static function removeSession(string $key): void {
      self::startSession();
      if (isset($_SESSION[$key])) {
         unset($_SESSION[$key]);
      }
   }

   /**
    * Destruye la sesión actual y elimina todos los datos almacenados.
    *
    * @return void
    */
   public static function destroySession(): void {
      if (session_status() === PHP_SESSION_ACTIVE) {
         session_unset();
         session_destroy();
      }
   }

   /**
    * Comprueba si existe una clave en la sesión.
    *
    * @param string $key Clave a verificar.
    * @return bool True si la clave existe, de lo contrario False.
    */
   public static function hasSession(string $key): bool {
      self::startSession();
      return isset($_SESSION[$key]);
   }

   /**
    * Devuelve todas las variables de sesión actuales.
    *
    * @return array Variables de sesión actuales.
    */
   public static function getAllSessions(): array {
      self::startSession();
      return $_SESSION ?? [];
   }

   /**
    * Regenera el ID de la sesión por razones de seguridad.
    *
    * @param bool $deleteOldSession Si debe eliminarse la sesión antigua.
    * @return void
    */
   public static function regenerateSessionId(bool $deleteOldSession = true): void {
      self::startSession();
      session_regenerate_id($deleteOldSession);
   }
}