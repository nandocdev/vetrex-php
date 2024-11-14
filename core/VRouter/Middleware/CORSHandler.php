<?php
/**
 * @package     core/VRouter
 * @subpackage  Middleware
 * @file        CORSHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:53:16
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Middleware;

use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;

class CORSHandler {
   public function __invoke(Request $request, Response $response): void {
      $response->addHeader('Access-Control-Allow-Origin', '*');
      $response->addHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
      $response->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
   }
}