<?php
/**
 * @package     core/RenderX
 * @subpackage  Components
 * @file        RenderView
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-18 10:24:40
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Components;
use Vertex\Core\RenderX\Components\DataHandler;
class RenderView {
   private DataHandler $dataHandler;
   private string $content;
   private array $chunks = [];


   public function __construct() {
      $this->dataHandler = new DataHandler();
   }

   public function load(string $file): string {
      if (!file_exists($file)) {
         throw new \InvalidArgumentException("El archivo de vista no existe: $file");
      }
      return file_get_contents($file);
   }



   // convertir un string con sintaxis php en un codigo ejecutable
   public function compile(string $content, array $data): string {
      $this->dataHandler->setData($data);
      extract($this->dataHandler->prepareDatForView());

      ob_start();
      eval ('?>' . $content);
      return ob_get_clean();
   }


}