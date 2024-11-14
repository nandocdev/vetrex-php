<?php
/**
 * @package     core/VQuery
 * @subpackage  Utils
 * @file        QueryValidator
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:36:56
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Utils;
use Vertex\Core\VQuery\Exceptions\InvalidQueryException;
use Vertex\Core\VQuery\Exceptions\SQLInjectionException;
class QueryValidator {
   // Validar la consulta SQL
   public function validateQuery(string $query): bool {
      // Validar que la consulta no esté vacía
      if (empty($query)) {
         throw new InvalidQueryException("Query cannot be empty");
      }

      // Validar que la consulta no contenga palabras clave peligrosas
      $this->validateForDangerousKeywords($query);

      // Se podría agregar más validaciones dependiendo del motor de base de datos
      $this->validateForDatabaseSpecificKeywords($query);

      return true;
   }

   // Validar palabras clave peligrosas comunes en cualquier base de datos
   private function validateForDangerousKeywords(string $query): void {
      $dangerousKeywords = ['DROP', 'TRUNCATE', 'DELETE', 'ALTER', 'EXEC', 'SHUTDOWN'];

      foreach ($dangerousKeywords as $keyword) {
         if (stripos($query, $keyword) !== false) {
            throw new InvalidQueryException("Query contains potentially dangerous keyword: {$keyword}");
         }
      }
   }

   // Validaciones específicas para el motor de base de datos (MySQL, PostgreSQL, etc.)
   private function validateForDatabaseSpecificKeywords(string $query): void {
      // Ejemplo de validación específica para MySQL
      if (stripos($query, 'LOAD DATA INFILE') !== false) {
         throw new InvalidQueryException("Query contains dangerous MySQL-specific keyword: LOAD DATA INFILE");
      }

      // Validaciones adicionales para otros motores como PostgreSQL, MSSQL, etc.
      // Ejemplo: Comando 'COPY' es peligroso en PostgreSQL
      if (stripos($query, 'COPY') !== false) {
         throw new InvalidQueryException("Query contains dangerous PostgreSQL-specific keyword: COPY");
      }
   }

   // Validar parámetros para evitar inyecciones SQL
   public function validateParams(array $params): bool {
      if (empty($params)) {
         throw new \InvalidArgumentException("Params cannot be empty");
      }

      // Revisión de posibles inyecciones SQL en los parámetros
      $this->validateInjection($params);

      return true;
   }

   // Evaluar si los parámetros contienen posibles inyecciones SQL
   private function validateInjection(array $params): void {
      foreach ($params as $param) {
         if (preg_match('/\b(union|select|from|where|insert|delete|update|drop|truncate|create|alter|exec|execute|declare|xp_cmdshell|sp_|xp_)/i', $param)) {
            throw new SQLInjectionException("Parameter contains potentially dangerous SQL keywords: {$param}");
         }
      }
   }
}