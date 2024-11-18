<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        DebugHandler.php
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 20:21:53
 * @version     1.0.0
 * @description Clase que proporciona herramientas de depuración para las vistas, 
 *              incluyendo análisis del tiempo de ejecución y manejo de variables, 
 *              con un enfoque en la mejora de la experiencia de desarrollo.
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;

use \InvalidArgumentException;
use \Exception;

class DebugHandler {
   private bool $debugMode = false;
   private float $startTime;
   private string $outputMethod = 'html'; // 'html', 'console', 'file'
   private array $debugStyles = [
      'position' => 'fixed',
      'bottom' => '0',
      'left' => '0',
      'background' => '#fff',
      'border' => '1px solid #ccc',
      'padding' => '10px',
      'z-index' => '9999',
      'width' => '300px',
      'font-family' => 'Arial, sans-serif',
      'font-size' => '14px',
      'color' => '#000'
   ];
   private string $logFilePath = '/tmp/debug.log'; // Ruta por defecto para logs

   /**
    * Constructor de la clase DebugHandler.
    *
    * @param bool $debugMode Indica si el modo de depuración está activado.
    * @param string $outputMethod Método de salida inicial ('html', 'console', 'file').
    * @param string $logFilePath Ruta del archivo de log si el método es 'file'.
    * @throws InvalidArgumentException Si el método de salida no es válido.
    */
   public function __construct(bool $debugMode = false, string $outputMethod = 'html', string $logFilePath = '/tmp/debug.log') {
      $this->debugMode = $debugMode;
      $this->startTime = microtime(true);
      $this->setOutputMethod($outputMethod);
      if ($outputMethod === 'file') {
         $this->setLogFilePath($logFilePath);
      }
   }

   /**
    * Habilita o deshabilita el modo de depuración.
    *
    * @param bool $enable True para habilitar, false para deshabilitar.
    * @return void
    */
   public function enable(bool $enable = true): void {
      $this->debugMode = $enable;
   }

   /**
    * Deshabilita el modo de depuración.
    *
    * @return void
    */
   public function disable(): void {
      $this->debugMode = false;
   }

   /**
    * Indica si el modo de depuración está habilitado.
    *
    * @return bool True si está habilitado, false en caso contrario.
    */
   public function isEnabled(): bool {
      return $this->debugMode;
   }

   /**
    * Establece el método de salida de la depuración.
    *
    * @param string $method El método de salida ('html', 'console', 'file').
    * @return void
    * @throws InvalidArgumentException Si el método de salida no es válido.
    */
   public function setOutputMethod(string $method): void {
      $allowedMethods = ['html', 'console', 'file'];
      if (!in_array($method, $allowedMethods, true)) {
         throw new InvalidArgumentException("Método de salida no válido. Opciones válidas: 'html', 'console', 'file'");
      }
      $this->outputMethod = $method;
   }

   /**
    * Obtiene el método de salida actual.
    *
    * @return string Método de salida actual.
    */
   public function getOutputMethod(): string {
      return $this->outputMethod;
   }

   /**
    * Establece la ruta del archivo de log para el método 'file'.
    *
    * @param string $path Ruta del archivo de log.
    * @return void
    * @throws InvalidArgumentException Si la ruta no es válida o no es escribible.
    */
   public function setLogFilePath(string $path): void {
      if (empty($path)) {
         throw new InvalidArgumentException("La ruta del archivo de log no puede estar vacía.");
      }
      $dir = dirname($path);
      if (!is_dir($dir)) {
         throw new InvalidArgumentException("El directorio para el archivo de log no existe: $dir");
      }
      if (!is_writable($dir)) {
         throw new InvalidArgumentException("El directorio para el archivo de log no es escribible: $dir");
      }
      $this->logFilePath = $path;
   }

   /**
    * Obtiene la ruta del archivo de log.
    *
    * @return string Ruta del archivo de log.
    */
   public function getLogFilePath(): string {
      return $this->logFilePath;
   }

   /**
    * Establece los estilos de depuración.
    *
    * @param array $styles Array asociativo de estilos CSS.
    * @return void
    */
   public function setDebugStyles(array $styles): void {
      foreach ($styles as $key => $value) {
         $this->debugStyles[$key] = $value;
      }
   }

   /**
    * Obtiene los estilos de depuración en formato string.
    *
    * @return string Estilos CSS concatenados.
    */
   private function getDebugStyles(): string {
      $style = '';
      foreach ($this->debugStyles as $key => $value) {
         $style .= "$key: $value; ";
      }
      return $style;
   }

   /**
    * Muestra información de depuración.
    *
    * @param mixed $variable La variable a mostrar.
    * @param string|null $label Una etiqueta para la variable (opcional).
    * @return void
    * @throws Exception Si ocurre un error al escribir en el archivo de log.
    */
   public function debug($variable, ?string $label = null): void {
      if (!$this->debugMode) {
         return;
      }

      switch ($this->outputMethod) {
         case 'html':
            $this->outputHtml($variable, $label);
            break;
         case 'console':
            $this->outputConsole($variable, $label);
            break;
         case 'file':
            $this->outputFile($variable, $label);
            break;
      }
   }

   /**
    * Salida de depuración en formato HTML.
    *
    * @param mixed $variable La variable a mostrar.
    * @param string|null $label Una etiqueta para la variable (opcional).
    * @return void
    */
   private function outputHtml($variable, ?string $label): void {
      echo "<div style='" . htmlspecialchars($this->getDebugStyles(), ENT_QUOTES, 'UTF-8') . "'>";
      if ($label) {
         echo "<strong>" . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ":</strong><br>";
      }
      echo "<pre>" . htmlspecialchars(print_r($variable, true), ENT_QUOTES, 'UTF-8') . "</pre>";
      echo "</div>";
   }

   /**
    * Salida de depuración en la consola del navegador.
    *
    * @param mixed $variable La variable a mostrar.
    * @param string|null $label Una etiqueta para la variable (opcional).
    * @return void
    */
   private function outputConsole($variable, ?string $label): void {
      $output = $label ? "$label: " : "";
      $output .= json_encode($variable, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
      echo "<script>console.log(" . json_encode($output) . ");</script>";
   }

   /**
    * Salida de depuración en un archivo de log.
    *
    * @param mixed $variable La variable a mostrar.
    * @param string|null $label Una etiqueta para la variable (opcional).
    * @return void
    * @throws Exception Si ocurre un error al escribir en el archivo de log.
    */
   private function outputFile($variable, ?string $label): void {
      $executionTime = $this->getExecutionTime();
      $logEntry = "[" . date('Y-m-d H:i:s') . "] ";
      if ($label) {
         $logEntry .= "$label: ";
      }
      $logEntry .= print_r($variable, true);
      $logEntry .= " | Execution Time: " . number_format($executionTime, 4) . " seconds\n";

      if (file_put_contents($this->logFilePath, $logEntry, FILE_APPEND | LOCK_EX) === false) {
         throw new Exception("Error al escribir en el archivo de log: {$this->logFilePath}");
      }
   }

   /**
    * Obtiene el tiempo de ejecución.
    *
    * @return float El tiempo de ejecución en segundos.
    */
   public function getExecutionTime(): float {
      return microtime(true) - $this->startTime;
   }

   /**
    * Obtiene la información de depuración como una cadena HTML.
    *
    * @return string La información de depuración formateada.
    */
   public function getOutput(): string {
      if (!$this->debugMode || $this->outputMethod !== 'html') {
         return '';
      }

      $executionTime = $this->getExecutionTime();
      $output = "<div style='" . htmlspecialchars($this->getDebugStyles(), ENT_QUOTES, 'UTF-8') . "'>";
      $output .= "<h3>Información de Depuración</h3>";
      $output .= "<p>Tiempo de Ejecución: " . number_format($executionTime, 4) . " segundos</p>";
      $output .= "</div>";
      return $output;
   }

   /**
    * Inyecta la información de depuración en el contenido HTML.
    *
    * @param string $content El contenido original.
    * @return string El contenido con la información de depuración inyectada.
    */
   public function injectDebugInfo(string $content): string {
      if (!$this->debugMode && $this->outputMethod !== 'html') {
         return $content;
      }

      return $content . $this->getOutput();
   }

   /**
    * Establece estilos de depuración personalizados.
    *
    * @param array $styles Array asociativo de estilos CSS.
    * @return void
    */
   public function addDebugStyles(array $styles): void {
      foreach ($styles as $key => $value) {
         $this->debugStyles[$key] = $value;
      }
   }

   /**
    * Restablece los estilos de depuración a los valores predeterminados.
    *
    * @return void
    */
   public function resetDebugStyles(): void {
      $this->debugStyles = [
         'position' => 'fixed',
         'bottom' => '0',
         'left' => '0',
         'background' => '#fff',
         'border' => '1px solid #ccc',
         'padding' => '10px',
         'z-index' => '9999',
         'width' => '300px',
         'font-family' => 'Arial, sans-serif',
         'font-size' => '14px',
         'color' => '#000'
      ];
   }

   /**
    * Establece el archivo de log para el método 'file'.
    *
    * @param string $path Ruta del archivo de log.
    * @return void
    * @throws InvalidArgumentException Si la ruta no es válida o no es escribible.
    */
   public function configureLogFile(string $path): void {
      $this->setLogFilePath($path);
   }
}