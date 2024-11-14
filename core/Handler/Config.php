<?php
/**
 * @package     vertex/core
 * @subpackage  Handler
 * @file        Config
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:02:39
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Handler;

class Config {
   protected array $config = [];
   private string $path;
   public function __construct() {
      $this->path = dirname(__DIR__, 2) . '/config';
      $this->load($this->path . '/app.config.php');
      $this->load($this->path . '/db.config.php');
      $this->config['app']['url'] = $this->getUrl();

   }

   // Cargar el archivo de configuración
   private function load(string $configFile) {
      if (!file_exists($configFile)) {
         throw new \Exception("El archivo de configuración no existe: {$configFile}");
      }
      $config = require $configFile;
      $this->config = array_merge_recursive($this->config, $config);
   }

   // Obtener una configuración
   public function get(string $key, $default = null) {
      $keys = explode('.', $key);
      $config = $this->config;

      foreach ($keys as $key) {
         if (isset($config[$key])) {
            $config = $config[$key];
         } else {
            return $default;
         }
      }

      return $config;
   }

   // Método para verificar si existe una clave en la configuración
   public function has(string $key): bool {
      $keys = explode('.', $key);
      $config = $this->config;

      foreach ($keys as $key) {
         if (!isset($config[$key])) {
            return false;
         }
         $config = $config[$key];
      }

      return true;
   }

   // obtiene la url base del proyecto
   private function getUrl(): string {
      if (!isset($_SERVER['HTTP_HOST'])) {
         return '';
      }
      $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
      $host = $_SERVER['HTTP_HOST'];
      $uri = $protocol . '://' . $host;
      if (!empty($_GET['url'])) {
         $query_string = '';
         if (count($_GET) > 1) {
            $query_string = '?';
            foreach ($_GET as $key => $value) {
               if ($key != 'url') {
                  $query_string .= $key . '=' . $value . '&';
               }
            }
            $query_string = rtrim($query_string, '&');
         }
         $uri .= str_replace($_GET['url'] . $query_string, '', urldecode($_SERVER['REQUEST_URI']));
      } else {
         $uri = $_SERVER['REQUEST_URI'];
      }
      return $uri;
   }

}