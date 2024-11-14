<?php
/**
 * @package     core/VRouter
 * @subpackage  Middleware
 * @file        CsrfMiddleware
 * @author      Fernando Castillo <ferncastillo@css.gob.pa>
 * @date        2024-11-14 15:00:17
 * @version     1.0.0
 * @description
 */

declare(strict_types=1);

namespace Vertex\Core\VRouter\Middleware;

use Vertex\Core\VRouter\Http\Request;
use Vertex\Core\VRouter\Http\Response;
use Vertex\Core\VRouter\Handler\MiddlewareHandler;

class CsrfMiddleware {
   // Nombre de la sesión donde se almacenará el token CSRF
   private const CSRF_TOKEN_SESSION_KEY = '_csrf_token';

   /**
    * Procesa la solicitud y valida el token CSRF.
    *
    * @param Request $request
    * @param Response $response
    * @return bool
    */
   public function handle(Request $request, Response $response): bool {
      // Solo valida CSRF en métodos de modificación de datos
      if (in_array($request->method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
         $csrfToken = $request->getHeader('X-CSRF-Token') ?? $request->data->get('_csrf_token');

         if (!$csrfToken || !$this->isValidCsrfToken($csrfToken)) {
            // Si el token no es válido o no existe, envía una respuesta de error
            $response->json(['error' => 'Token CSRF no válido o ausente'])->setStatus(403);
            return false;
         }
      }

      // Si el token es válido o la solicitud no requiere CSRF, continuar
      return true;
   }

   /**
    * Genera un nuevo token CSRF y lo almacena en la sesión.
    *
    * @return string El token CSRF generado.
    */
   public static function generateCsrfToken(): string {
      // Generar un token CSRF aleatorio y almacenarlo en sesión
      $token = bin2hex(random_bytes(32));
      $_SESSION[self::CSRF_TOKEN_SESSION_KEY] = $token;
      return $token;
   }

   /**
    * Verifica si el token CSRF proporcionado es válido.
    *
    * @param string $csrfToken El token CSRF a verificar.
    * @return bool True si el token es válido; False en caso contrario.
    */
   private function isValidCsrfToken(string $csrfToken): bool {
      return isset($_SESSION[self::CSRF_TOKEN_SESSION_KEY]) &&
         hash_equals($_SESSION[self::CSRF_TOKEN_SESSION_KEY], $csrfToken);
   }
}