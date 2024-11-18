<?php
/**
 * @package     core/RenderX
 * @subpackage  Core
 * @file        PartialLoader.php
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 20:21:53
 * @version     1.0.0
 * @description Clase especializada en la carga y renderización de componentes 
 *              parciales, permitiendo su reutilización a lo largo de diferentes 
 *              vistas y layouts en el proyecto.
 */

declare(strict_types=1);

namespace Vertex\Core\RenderX\Core;
use Vertex\Core\RenderX\Interfaces\PartialLoaderInterface;
use Vertex\Core\RenderX\Interfaces\DataHandlerInterface;
use Vertex\Core\Handler\Exceptions\FileNotFoundException;
use Vertex\Core\Handler\Config;

class PartialLoader implements PartialLoaderInterface {
   private DataHandlerInterface $dataHandler;
   private string $partialsPath;
   private string $partialType; // define si el partial es parte de un layout o de una vista
   private Config $config;
   private const DS = DIRECTORY_SEPARATOR;


   public function __construct(DataHandlerInterface $dataHandler, string $partialsPath) {
      $this->config = new Config();
      $this->dataHandler = $dataHandler;
      $this->setPartialsPath($partialsPath);
   }

   public function setPartialsPath(string $partialsPath): void {
      $this->partialsPath = rtrim($partialsPath, self::DS) . self::DS . 'partials' . self::DS;

      if (!is_dir($this->partialsPath)) {
         throw new \InvalidArgumentException("La ruta de partials '$partialsPath' no es un directorio válido.");
      }
   }

   public function import(string $partial, array $data = [], bool $returnAsString = false): mixed {
      //Soporte para subdirectorios usando puntos como separadores
      $partialPath = $this->partialsPath . str_replace('.', self::DS, $partial) . '.phtml';

      if (!file_exists($partialPath)) {
         throw new FileNotFoundException("El parcial '{$partial}' no se encontró en el directorio '{$this->partialsPath}'.");
      }

      // Prepara datos.  En lugar de usar extract(), se pasa el array directamente a la vista.
      $data = $this->dataHandler->prepareDataForView();
      $data = array_merge($data, $this->dataHandler->getData()); // se agregan los datos adicionales al array principal
      $data = array_merge($data, $data); // se agregan los datos del array $data


      if ($returnAsString) {
         ob_start();
         include $partialPath;
         return ob_get_clean();
      }

      include $partialPath;
      return null; //Retorna null si no se requiere string
   }

}