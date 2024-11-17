<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        ApiConsumerHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 21:11:15
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;
use \Exception;
class ApiConsumerHelper {
   private $baseUrl;
   private $headers = [];

   public function __construct($baseUrl) {
      $this->baseUrl = $baseUrl;
   }

   public function setHeader($key, $value) {
      $this->headers[$key] = $value;
   }

   public function get($endpoint, array $params = []) {
      $url = $this->buildUrl($endpoint, $params);
      return $this->request('GET', $url);
   }

   public function post($endpoint, array $data = []) {
      $url = $this->buildUrl($endpoint);
      return $this->request('POST', $url, $data);
   }

   public function put($endpoint, array $data = []) {
      $url = $this->buildUrl($endpoint);
      return $this->request('PUT', $url, $data);
   }

   public function delete($endpoint, array $data = []) {
      $url = $this->buildUrl($endpoint);
      return $this->request('DELETE', $url, $data);
   }

   private function buildUrl($endpoint, array $params = []) {
      $url = $this->baseUrl . $endpoint;
      if (!empty($params)) {
         $url .= '?' . http_build_query($params);
      }
      return $url;
   }

   private function request($method, $url, array $data = []) {
      $curl = curl_init($url);
      curl_setopt_array($curl, [
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_CUSTOMREQUEST => $method,
         CURLOPT_HTTPHEADER => $this->headers,
      ]);

      if (!empty($data)) {
         curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      }

      $response = curl_exec($curl);
      $error = curl_error($curl);
      curl_close($curl);

      if ($error) {
         throw new Exception('Error al realizar la solicitud: ' . $error);
      }

      return json_decode($response, true); // Asumimos respuesta JSON
   }
}