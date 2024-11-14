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
class QueryExecutor {
   private PDO $connection;

   public function __construct(DatabaseConnection $dbConnection) {
      $this->connection = $dbConnection->getConnection();
   }

   // Método centralizado para ejecutar consultas y transacciones
   private function execute(string $query, array $params = [], bool $fetchAll = false, bool $isTransaction = false): QueryResult {
      try {
         if ($isTransaction) {
            $this->connection->beginTransaction();
         }

         $stmt = $this->connection->prepare($query);
         $stmt->execute($params);

         $result = $fetchAll ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);

         if ($isTransaction) {
            $this->connection->commit();
         }

         // Si no hay resultados y se esperaba, devolvemos un mensaje específico
         if (empty($result) && $fetchAll) {
            return new QueryResult(false, [], 'No results found.');
         }

         return new QueryResult(true, $result);

      } catch (PDOException $e) {
         // Manejo de excepciones detallado
         if ($isTransaction) {
            $this->connection->rollBack();
         }

         // Errores específicos de SQL
         if ($e->getCode() === '42S02') {
            throw new DatabaseQueryException("Table not found: {$e->getMessage()}", $e->getCode());
         } elseif ($e->getCode() === '42000') {
            throw new DatabaseQueryException("SQL syntax error: {$e->getMessage()}", $e->getCode());
         }

         // Manejo general de excepciones
         throw new DatabaseQueryException("Query execution failed: {$e->getMessage()}", $e->getCode());
      }
   }

   // Ejecuta una consulta sin retorno de resultados (solo ejecución)
   public function executeQuery(string $query, array $params = []): QueryResult {
      return $this->execute($query, $params, false);
   }

   // Ejecuta una consulta de transacción
   public function executeTransaction(string $query, array $params = []): QueryResult {
      return $this->execute($query, $params, false, true);
   }

   // Obtiene todos los resultados de una consulta
   public function fetchAll(string $query, array $params = []): QueryResult {
      return $this->execute($query, $params, true);
   }

   // Obtiene un solo resultado de una consulta
   public function fetchOne(string $query, array $params = []): QueryResult {
      return $this->execute($query, $params, false);
   }
}