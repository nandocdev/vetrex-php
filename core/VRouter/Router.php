<?php
/**
 * @package     vertex/core
 * @subpackage  VRouter
 * @file        Router
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 22:55:25
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VRouter;

use DI\NotFoundException;
use Vertex\Core\VRouter\Collection\RouteCollection;
use Vertex\Core\VRouter\Handler\NotFoundHttpException;
use Vertex\Core\VRouter\Matcher\RouteMatcher;
use Vertex\Core\VRouter\Handler\ControllerInvoker;
use Vertex\Core\VRouter\Handler\MiddlewareHandler;
use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;
use Psr\Container\ContainerInterface;

class Router {
   private RouteCollection $routeCollection;
   private RouteMatcher $routeMatcher;
   private MiddlewareHandler $middlewareHandler;
   private ControllerInvoker $controllerInvoker;
   private ContainerInterface $container;
   private array $globalMiddlewares = [];

   public function __construct(
      RouteCollection $routeCollection,
      RouteMatcher $routeMatcher,
      MiddlewareHandler $middlewareHandler,
      ControllerInvoker $controllerInvoker,
      ContainerInterface $container) {
      $this->routeCollection = $routeCollection;
      $this->routeMatcher = $routeMatcher;
      $this->middlewareHandler = $middlewareHandler;
      $this->controllerInvoker = $controllerInvoker;
      $this->container = $container;
   }

   public function addGlobalMiddleware(callable|string $middleware): void {
      $this->globalMiddlewares[] = $middleware;
   }

   public function get(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('GET', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function post(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('POST', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function put(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('PUT', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function delete(string $path, callable|array $callback, array $middlewares = []): void {
      $this->routeCollection->addRouter('DELETE', $path, $callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function redirect(string $from, string $to, int $statusCode = 301): void {
      header("Location: {$to}", true, $statusCode);
      exit();
   }

   public function group(callable $callback, array $middlewares = []): void {
      $this->routeCollection->group($callback, array_merge($this->globalMiddlewares, $middlewares));
   }

   public function dispatch(): void {
      $request = new Request();
      $response = new Response();

      try {
         // Match route
         $route = $this->routeMatcher->match($request->method, $request->url, $this->routeCollection);

         if ($route === null) {
            new NotFoundHttpException('Route not found');
            $this->handleRouteNotFound($response);
            return;
         }

         // Handle middlewares
         $this->middlewareHandler->handle($route['middlewares'], $request, $response);
         $request->addBody($route['params']);
         // Invoke controller
         $this->controllerInvoker->invoke($route['callback'], $request, $response);
      } catch (\Exception $e) {

         $this->handleServerError($response, $e);
      }
   }

   private function handleRouteNotFound(Response $response): void {
      $response->setStatus(404)->json(['error' => 'Route not found']);
   }

   private function handleServerError(Response $response, \Exception $e): void {
      $response->setStatus(500)->json(['error' => $e->getMessage()]);
   }

}