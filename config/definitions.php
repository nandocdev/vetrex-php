<?php
/**
 * @package     srv/vertex
 * @subpackage  config
 * @file        definitions
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 21:54:53
 * @version     1.0.0
 * @description: Definitions para php-di, maneja las dependencias de la aplicacion
 */
declare(strict_types=1);

return [
   // define dependencia de Config
   'config' => function () {
      new Vertex\Core\Handler\Config();
   },
];