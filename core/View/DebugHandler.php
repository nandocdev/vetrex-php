<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        DebugHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:24:33
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

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
      'z-index' => '9999'
   ];

   public function __construct(bool $debugMode = false) {
      $this->debugMode = $debugMode;
      $this->startTime = microtime(true);
   }



   public function enable(bool $enable = true) {
      $this->debugMode = $enable;
   }

   public function disable() {
      $this->debugMode = false;
   }

   public function isEnabled(): bool {
      return $this->debugMode;
   }

   public function setOutputMethod(string $method) {
      $this->outputMethod = $method;
   }

   private function getDebugStyles(): string {
      $style = '';
      foreach ($this->debugStyles as $key => $value) {
         $style .= "$key: $value; ";
      }
      return $style;
   }

   public function debug($variable, $label = null) {
      if ($this->debugMode) {
         if ($this->outputMethod === 'html') {
            echo "<pre>";
            if ($label) {
               echo "<strong>$label:</strong><br>";
            }
            var_dump($variable);
            echo "</pre>";
         } elseif ($this->outputMethod === 'console') {
            $output = $label ? "$label: " : "";
            $output .= json_encode($variable);
            echo "<script>console.log(" . json_encode($output) . ");</script>";
         }
      }
   }

   public function getExecutionTime(): float {
      return microtime(true) - $this->startTime;
   }

   public function getOutput(): string {
      if ($this->debugMode) {
         $executionTime = $this->getExecutionTime();
         $output = "<div style='" . $this->getDebugStyles() . "'>";
         $output .= "<h3>Debug Information</h3>";
         $output .= "<p>Execution Time: " . number_format($executionTime, 4) . " seconds</p>";
         $output .= "</div>";
         return $output;
      }
      return '';
   }

   public function injectDebugInfo(string $content): string {
      if ($this->debugMode) {
         return $content . $this->getOutput();
      }
      return $content;
   }
}