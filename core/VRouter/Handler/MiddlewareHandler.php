<?php
/**
 * @package     core/VRouter
 * @subpackage  Handler
 * @file        MiddlewareHandler
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:53:38
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter\Handler;

use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;
use Vertex\Core\VRouter\Interface\MiddlewareInterface;
use Psr\Container\ContainerInterface;

class MiddlewareHandler {
   private ContainerInterface $container;

   public function __construct(ContainerInterface $container) {
      $this->container = $container;
   }

   public function handle(array $middlewares, Request $request, Response $response): void {
      $this->executeMiddlewareChain($middlewares, $request, $response);
   }

   private function executeMiddlewareChain(array $middlewares, Request $request, Response $response, int $index = 0): void {
      if (!isset($middlewares[$index])) {
         return; // Fin de la cadena de middlewares
      }

      $middleware = $middlewares[$index];

      try {
         // Evaluar si es un callable o una clase middleware
         if (is_callable($middleware)) {
            $middleware($request, $response, function () use ($middlewares, $request, $response, $index) {
               $this->executeMiddlewareChain($middlewares, $request, $response, $index + 1);
            });
         } else {
            if (!class_exists($middleware)) {
               throw new \Exception("Middleware {$middleware} not found");
            }

            // Instanciar el middleware a través del contenedor
            $middlewareInstance = $this->container->get($middleware);

            if (!$middlewareInstance instanceof MiddlewareInterface) {
               throw new \Exception("Middleware {$middleware} must implement MiddlewareInterface");
            }

            // Ejecutar el método handle del middleware
            $middlewareInstance->handle($request, $response, function () use ($middlewares, $request, $response, $index) {
               $this->executeMiddlewareChain($middlewares, $request, $response, $index + 1);
            });
         }
      } catch (\Exception $e) {
         $response->setStatus(500)->json(['error_middleware' => $e->getMessage()]);
      }
   }
}