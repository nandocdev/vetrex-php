<?php
/**
 * @package     vertex/core
 * @subpackage  View
 * @file        PartialLoader
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 11:15:36
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\View;

use Vertex\Core\View\DataHandler;
use Vertex\Core\Handler\Exceptions\FileNotFoundException;

class PartialLoader {
   private $dataHandler;
   private $partialsPath;
   const DS = DIRECTORY_SEPARATOR;

   // TODO: revisar alcance de la propiedad $partialsPath
   public function __construct(DataHandler $dataHandler, $partialsPath = 'partials/') {
      $this->dataHandler = $dataHandler;
      $this->partialsPath = rtrim($partialsPath, self::DS) . self::DS;
   }

   public function import($partial, $returnAsString = false) {
      // Soporte para subdirectorios
      $partialPath = $this->partialsPath . str_replace('.', self::DS, $partial) . '.phtml';

      if (!file_exists($partialPath)) {
         throw new FileNotFoundException("El parcial '{$partial}' no se encontró en el directorio '{$this->partialsPath}'.");
      }

      // Prepara datos y extrae para la vista
      $data = $this->dataHandler->prepareDataForView();
      extract($data);

      if ($returnAsString) {
         ob_start();
         include $partialPath;
         return ob_get_clean();
      }

      include $partialPath;
   }
}