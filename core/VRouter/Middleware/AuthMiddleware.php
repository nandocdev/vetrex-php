<?php
/**
 * @package     core/VRouter
 * @subpackage  Middleware
 * @file        AuthMiddleware
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-14 14:52:47
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Vertex\Core\VRouter\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;
use Vertex\Core\VRouter\Handler\UnauthorizedHttpException;
use Exception;

class AuthMiddleware {
   protected string $jwtSecret;

   public function __construct() {
      // La clave secreta debe configurarse en las variables de entorno
      $this->jwtSecret = getenv('JWT_SECRET');
   }

   /**
    * Verifica la autenticación JWT.
    *
    * @param Request $request
    * @param callable $next
    * @return mixed
    * @throws UnauthorizedHttpException
    */
   public function handle(Request $request, callable $next) {
      $authHeader = $request->getHeader('Authorization');

      if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
         throw new UnauthorizedHttpException('Token no proporcionado o inválido.');
      }

      $token = substr($authHeader, 7); // Quita el prefijo "Bearer "

      try {
         $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
         $request->setUser($decoded); // Asigna los datos del usuario al Request
      } catch (Exception $e) {
         throw new UnauthorizedHttpException('Token inválido o expirado.');
      }

      // Pasa la solicitud al siguiente middleware o controlador
      return $next($request);
   }
}