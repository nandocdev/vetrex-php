<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        RenderView
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-19 15:17:54
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;

use Vertex\Core\Handler\Config;
use Vertex\Core\RenderX\Core\DataHandler;
use Vertex\Core\RenderX\Core\TemplateLoader;

class RenderView {
   private DataHandler $dataHandler;
   private TemplateLoader $templateLoader;
   private Config $config;
   private string $content;
   private array $chunks = [];

   public function __construct(Config $config, DataHandler $dataHandler, TemplateLoader $templateLoader) {
      $this->config = $config;
      $this->dataHandler = $dataHandler;
      $this->templateLoader = $templateLoader;
   }

   public function load(string $file): string {
      if (!file_exists($file)) {
         throw new \InvalidArgumentException("El archivo de vista no existe: $file");
      }
      return file_get_contents($file);
   }

   // metodo extractPartials, busca en el contenido de la vista los marcadores @partial('partial', ?'type') y genera la ruta del archivo partial
   public function extractPartials(string $content): string {
      // Patrón para capturar dos argumentos string.
      $pattern = '/@partial\((\'|")(?<partial>[^\'"]+)\1,\s*(\'|")(?<value>[^\'"]+)\3\)/';

      // Buscar todos los marcadores que coincidan con el patrón.
      preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

      foreach ($matches as $match) {
         $partialName = $match['partial']; // Primer argumento: nombre del parcial.
         $value = $match['value'] ?? '';         // Segundo argumento: valor.

         // Cargar y procesar el contenido del parcial con los valores.
         $partialContent = $this->templateLoader->loadPartial($partialName, $value);

         // Reemplazar el marcador en el contenido original.
         $content = str_replace($match[0], $partialContent, $content);
      }

      return $content;
   }
}