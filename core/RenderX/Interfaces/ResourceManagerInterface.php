<?php
/**
 * @package     core/RenderX
 * @subpackage  Interfaces
 * @file        ResourceManagerInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-17 11:09:35
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\RenderX\Interfaces;

interface ResourceManagerInterface {
   /**
    * Agrega una hoja de estilo.
    *
    * @param string $href URL o ruta de la hoja de estilo.
    * @param array $dependencies Dependencias de la hoja de estilo.
    * @param bool $inline Si es true, el estilo se incluye como texto en línea.
    * @return void
    */
   public function addStyle(string $href, array $dependencies = [], bool $inline = false): void;

   /**
    * Agrega un script.
    *
    * @param string $src URL o ruta del script.
    * @param array $dependencies Dependencias del script.
    * @param bool $inline Si es true, el script se incluye como texto en línea.
    * @return void
    */
   public function addScript(string $src, array $dependencies = [], bool $inline = false): void;


   /**
    * Renderiza las hojas de estilo.
    * @return string
    */
   public function renderStyles(): string;

   /**
    * Renderiza los scripts.
    * @return string
    */
   public function renderScripts(): string;

   /**
    * Agrega una ruta para buscar recursos.
    * @param string $path
    * @return void
    */
   public function addResourcePath(string $path): void;
}