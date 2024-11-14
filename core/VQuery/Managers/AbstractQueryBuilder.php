<?php
/**
 * @package     core/VQuery
 * @subpackage  Managers
 * @file        AbstractQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:45:14
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Managers;
use Vertex\Core\VQuery\Exceptions\DatabaseQueryException;
use Vertex\Core\VQuery\Connection\DatabaseConnection;
use Vertex\Core\VQuery\Handlers\QueryExecutor;
use Vertex\Core\VQuery\Handlers\QueryResult;
abstract class AbstractQueryBuilder {
   protected string $table;
   protected array $params = [];
   protected DatabaseConnection $con;

   // Inyección de dependencias para la conexión de base de datos
   public function __construct(DatabaseConnection $con, string $table) {
      $this->con = $con;
      $this->table = $table;
   }

   // Obtener los parámetros de la consulta
   public function getParams(): array {
      return $this->params;
   }

   // Métodos para agregar condiciones WHERE de forma flexible
   protected function buildWhere(array $conditions): string {
      $clauses = [];
      foreach ($conditions as $condition) {
         if (is_array($condition)) {
            $clauses[] = "{$condition['column']} {$condition['operator']} ?";
            $this->params[] = $condition['value'];
         }
      }
      return $clauses ? 'WHERE ' . implode(' AND ', $clauses) : '';
   }

   // Ejecutar la consulta, unifica el manejo de excepciones
   protected function executeQuery(string $sql): QueryResult {
      try {
         $query = new QueryExecutor($this->con);
         $result = $query->executeQuery($sql, $this->params);
         // obtain bool value for success parameter in QueryResult
         $success = is_array($result) ? true : $result;
         return new QueryResult($success, $result);
      } catch (\Exception $e) {
         throw new \RuntimeException("Error executing query: " . $e->getMessage());
      }
   }

   // Método abstracto para construir la consulta
   abstract public function build(): string;

   // Método abstracto para ejecutar la consulta
   abstract public function execute(): QueryResult;

   // Método centralizado para manejar excepciones
   protected function handleException(\PDOException $e, string $query): void {
      // Aquí se puede registrar el error o hacer un tratamiento específico
      throw new DatabaseQueryException(
         "Error executing query on table {$this->table}: {$e->getMessage()} - Query: {$query}",
         $e->getCode()
      );
   }
}