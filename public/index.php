<?php
/**
 * @package     srv/vertex
 * @subpackage  public
 * @file        index
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 21:38:41
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

use Kint\Kint;
use Vertex\Core\Bootstrap\App;

use Vertex\Core\VRouter\Collection\RouteCollection;
use Vertex\Core\VRouter\Matcher\RouteMatcher;
use Vertex\Core\VRouter\Router;
use Vertex\Core\VRouter\Handler\ControllerInvoker;
use Vertex\Core\VRouter\Handler\MiddlewareHandler;
use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;

// TODO: Deshabilitar en producción
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$vendor = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor)) {
   die('Please run: composer install');
}
require $vendor;

try {
   $container = Vertex\Core\Bootstrap\Container::build();
   App::run();

   // implementa gestión de errores
   set_error_handler('Vertex\Core\Handler\VertexExceptions::errorHandler');
   set_exception_handler('Vertex\Core\Handler\VertexExceptions::exceptionHandler');

   $router = new Router(
      new RouteCollection(),
      new RouteMatcher(),
      new MiddlewareHandler($container),
      new ControllerInvoker($container),
      $container
   );

   $router->get('/home', function (Request $request, Response $response) {
      $response->json(['message' => 'Welcome to the home page']);
   });

   $router->dispatch();

} catch (\Exception $e) {
   Kint::dump($e->getMessage());
}