<?php
/**
 * @package     core/VQuery
 * @subpackage  Handlers
 * @file        QueryExecutor
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:21:20
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Handlers;
use Vertex\Core\VQuery\Connection\DatabaseConnection;
use Vertex\Core\VQuery\Exceptions\DatabaseQueryException;
use PDO;
use PDOException;
class QueryExecutorCopy {
   private DatabaseConnection $dbConnection;

   public function __construct(DatabaseConnection $dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   // Método privado para centralizar la ejecución de las consultas
   private function execute(string $query, array $params = [], bool $fetchAll = false): array|bool {
      try {
         $pdo = $this->dbConnection->getConnection();
         $stmt = $pdo->prepare($query);
         $stmt->execute($params);

         if ($fetchAll) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
         }

         return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
      } catch (PDOException $e) {
         // Manejo de errores con detalles más específicos
         if ($e->getCode() === '42S02') {
            // Error de tabla no encontrada (SQLSTATE 42S02)
            throw new DatabaseQueryException("Table not found: {$e->getMessage()}", $e->getCode());
         } elseif ($e->getCode() === '42000') {
            // Error de sintaxis en SQL (SQLSTATE 42000)
            throw new DatabaseQueryException("SQL syntax error: {$e->getMessage()}", $e->getCode());
         }
         // Manejo general de errores
         throw new DatabaseQueryException("Query execution failed: {$e->getMessage()}", $e->getCode());
      }
   }

   // Método para ejecutar una consulta sin retorno de resultados (solo ejecutar)
   public function executeQuery(string $query, array $params = []): bool {
      try {
         $pdo = $this->dbConnection->getConnection();
         $stmt = $pdo->prepare($query);
         return $stmt->execute($params);
      } catch (PDOException $e) {
         throw new DatabaseQueryException("Query execution failed: " . $e->getMessage(), $e->getCode());
      }
   }

   // Método para obtener todos los resultados de una consulta
   public function fetchAll(string $query, array $params = []): array {
      return $this->execute($query, $params, true);
   }

   // Método para obtener un solo resultado de una consulta
   public function fetchOne(string $query, array $params = []): ?array {
      return $this->execute($query, $params, false);
   }
}