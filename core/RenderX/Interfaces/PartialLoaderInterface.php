<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        PartialLoaderInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:26
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface PartialLoaderInterface {
   /**
    * Importa un partial.
    *
    * @param string $partial Nombre del partial (sin extensión).  Puede incluir puntos para subcarpetas.
    * @param array $data Datos a pasar al partial.
    * @param bool $returnAsString Si es true, devuelve el contenido del partial como string; de lo contrario, lo incluye directamente.
    * @return string|null El contenido del partial si $returnAsString es true, null en caso contrario.
    * @throws \Vertex\Core\Handler\Exceptions\FileNotFoundException Si el partial no se encuentra.
    */
   public function import(string $partial, array $data = [], bool $returnAsString = false): mixed;
}