<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        HttpHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:12:02
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \Exception;

class HttpHelper {
   /**
    * Realiza una solicitud GET.
    *
    * @param string $url URL del recurso.
    * @param array $params Parámetros de la consulta.
    * @return array Respuesta decodificada en formato array.
    * @throws Exception Si ocurre un error en la solicitud.
    */
   public static function get(string $url, array $params = []): array {
      $query = http_build_query($params);
      $finalUrl = $query ? "$url?$query" : $url;
      return self::sendRequest('GET', $finalUrl);
   }

   /**
    * Realiza una solicitud POST.
    *
    * @param string $url URL del recurso.
    * @param array $params Datos para enviar en el cuerpo de la solicitud.
    * @return array Respuesta decodificada en formato array.
    * @throws Exception Si ocurre un error en la solicitud.
    */
   public static function post(string $url, array $params = []): array {
      return self::sendRequest('POST', $url, $params);
   }

   /**
    * Realiza una solicitud PUT.
    *
    * @param string $url URL del recurso.
    * @param array $params Datos para enviar en el cuerpo de la solicitud.
    * @return array Respuesta decodificada en formato array.
    * @throws Exception Si ocurre un error en la solicitud.
    */
   public static function put(string $url, array $params = []): array {
      return self::sendRequest('PUT', $url, $params);
   }

   /**
    * Realiza una solicitud DELETE.
    *
    * @param string $url URL del recurso.
    * @param array $params Datos para enviar en la consulta.
    * @return array Respuesta decodificada en formato array.
    * @throws Exception Si ocurre un error en la solicitud.
    */
   public static function delete(string $url, array $params = []): array {
      $query = http_build_query($params);
      $finalUrl = $query ? "$url?$query" : $url;
      return self::sendRequest('DELETE', $finalUrl);
   }

   /**
    * Método privado para enviar solicitudes HTTP con cURL.
    *
    * @param string $method Método HTTP (GET, POST, PUT, DELETE).
    * @param string $url URL del recurso.
    * @param array $data Datos a enviar en el cuerpo de la solicitud.
    * @return array Respuesta decodificada en formato array.
    * @throws Exception Si ocurre un error en la solicitud o la respuesta no es válida.
    */
   private static function sendRequest(string $method, string $url, array $data = []): array {
      $ch = curl_init();

      $options = [
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_CUSTOMREQUEST => $method,
         CURLOPT_TIMEOUT => 30,
      ];

      if (in_array($method, ['POST', 'PUT'])) {
         $options[CURLOPT_POSTFIELDS] = json_encode($data);
         $options[CURLOPT_HTTPHEADER] = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data)),
         ];
      }

      curl_setopt_array($ch, $options);
      $response = curl_exec($ch);
      $error = curl_error($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($error) {
         throw new Exception("Error en la solicitud cURL: $error");
      }

      if ($httpCode < 200 || $httpCode >= 300) {
         throw new Exception("Error en la respuesta HTTP: Código $httpCode");
      }

      $decoded = json_decode($response, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
         throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
      }

      return $decoded;
   }
}