<?php
/**
 * @package     core/VRouter
 * @subpackage  Interface
 * @file        MiddlewareInterface
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:54:39
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Interface;
use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;
interface MiddlewareInterface {
   public function handle(Request $request, Response $response, callable $next): void;
}