<?php
/**
 * @package     vertex/core
 * @subpackage  Helpers
 * @file        EmailHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-16 22:14:36
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\Helpers;

use \InvalidArgumentException;

class EmailHelper {
   /**
    * Envía un correo electrónico.
    *
    * @param string $to Dirección de correo del destinatario.
    * @param string $subject Asunto del correo.
    * @param string $body Contenido del correo.
    * @param array $headers Encabezados adicionales (ej. ['From' => 'example@example.com']).
    * @return bool Indica si el correo fue enviado exitosamente.
    */
   public static function sendEmail(string $to, string $subject, string $body, array $headers = []): bool {
      if (!self::validateEmailAddress($to)) {
         throw new InvalidArgumentException("La dirección de correo electrónico no es válida: $to");
      }

      $formattedHeaders = [];
      foreach ($headers as $key => $value) {
         $formattedHeaders[] = "$key: $value";
      }

      return mail($to, $subject, $body, implode("\r\n", $formattedHeaders));
   }

   /**
    * Valida si una dirección de correo electrónico tiene un formato correcto.
    *
    * @param string $email Dirección de correo electrónico a validar.
    * @return bool True si el correo tiene un formato válido, false en caso contrario.
    */
   public static function validateEmailAddress(string $email): bool {
      return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
   }

   /**
    * Genera un enlace mailto para crear un nuevo correo.
    *
    * @param string $email Dirección de correo del destinatario.
    * @param string $subject Asunto predeterminado del correo.
    * @param string $body Cuerpo predeterminado del correo.
    * @return string Enlace mailto generado.
    */
   public static function generateMailtoLink(string $email, string $subject = '', string $body = ''): string {
      if (!self::validateEmailAddress($email)) {
         throw new InvalidArgumentException("La dirección de correo electrónico no es válida: $email");
      }

      $params = [];
      if ($subject) {
         $params['subject'] = $subject;
      }
      if ($body) {
         $params['body'] = $body;
      }

      $query = http_build_query($params);
      return "mailto:$email" . ($query ? "?$query" : '');
   }
}