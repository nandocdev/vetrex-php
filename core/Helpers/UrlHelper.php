<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        UrlHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:02:58
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

class UrlHelper {
   /**
    * Construye una URL a partir de un array de parámetros.
    *
    * @param array $params Array de parámetros (incluyendo 'base' si es necesario).
    * @return string La URL construida.
    */
   public static function buildUrl(array $params): string {
      $base = $params['base'] ?? '';
      unset($params['base']);
      return $base . '?' . http_build_query($params);
   }

   /**
    * Extrae un parámetro de una URL.
    *
    * @param string $url La URL completa.
    * @param string $key La clave del parámetro a extraer.
    * @return string|null El valor del parámetro o null si no existe.
    */
   public static function getQueryParam(string $url, string $key): ?string {
      $parsedUrl = parse_url($url);
      if (!isset($parsedUrl['query'])) {
         return null;
      }
      parse_str($parsedUrl['query'], $queryParams);
      return $queryParams[$key] ?? null;
   }

   /**
    * Verifica si una URL es válida.
    *
    * @param string $url La URL a verificar.
    * @return bool True si es válida, False en caso contrario.
    */
   public static function isValidUrl(string $url): bool {
      return filter_var($url, FILTER_VALIDATE_URL) !== false;
   }

   /**
    * Devuelve la URL base de la aplicación.
    *
    * @return string La URL base.
    */
   public static function getBaseUrl(): string {
      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
      $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
      $script = $_SERVER['SCRIPT_NAME'] ?? '';
      $path = dirname($script);
      return rtrim("$protocol://$host$path", '/');
   }

   /**
    * Limpia una URL de caracteres no seguros.
    *
    * @param string $url La URL a limpiar.
    * @return string La URL limpia.
    */
   public static function sanitizeUrl(string $url): string {
      return filter_var($url, FILTER_SANITIZE_URL);
   }

   /**
    * Obtiene la ruta relativa de una URL.
    *
    * @param string $url La URL completa.
    * @return string|null La ruta relativa o null si no se puede obtener.
    */
   public static function getPath(string $url): ?string {
      $parsedUrl = parse_url($url);
      return $parsedUrl['path'] ?? null;
   }

   /**
    * Convierte una URL relativa en una URL absoluta.
    *
    * @param string $relativeUrl La URL relativa.
    * @return string La URL absoluta.
    */
   public static function makeAbsoluteUrl(string $relativeUrl): string {
      return rtrim(self::getBaseUrl(), '/') . '/' . ltrim($relativeUrl, '/');
   }

   /**
    * Agrega o actualiza parámetros en una URL.
    *
    * @param string $url La URL original.
    * @param array $params Los parámetros a agregar o actualizar.
    * @return string La URL con los parámetros modificados.
    */
   public static function updateQueryParams(string $url, array $params): string {
      $parsedUrl = parse_url($url);
      parse_str($parsedUrl['query'] ?? '', $queryParams);
      $queryParams = array_merge($queryParams, $params);
      $queryString = http_build_query($queryParams);
      $scheme = $parsedUrl['scheme'] ?? 'http';
      $host = $parsedUrl['host'] ?? '';
      $path = $parsedUrl['path'] ?? '';
      return "$scheme://$host$path?$queryString";
   }
}