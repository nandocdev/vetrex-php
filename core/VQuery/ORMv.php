<?php
/**
 * @package     vertex/core
 * @subpackage  VQuery
 * @file        ORMv
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-13 23:39:01
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery;
use Vertex\Core\VQuery\Builders\InsertQueryBuilder;
use Vertex\Core\VQuery\Builders\UpdateQueryBuilder;
use Vertex\Core\VQuery\Builders\DeleteQueryBuilder;
use Vertex\Core\VQuery\Builders\SelectQueryBuilder;
use Vertex\Core\VQuery\Connection\DatabaseConnection;
use Vertex\Core\VQuery\Managers\AbstractQueryBuilder;


class ORMv {
   private string $table;
   private DatabaseConnection $connection;

   // Constructor con inyección de dependencias para la conexión y la tabla
   public function __construct(DatabaseConnection $connection, string $table) {
      $this->connection = $connection;
      $this->table = $table;
   }

   // Método para generar consultas SELECT
   public function select(): AbstractQueryBuilder {
      return new SelectQueryBuilder($this->connection, $this->table);
   }

   // Método para generar consultas INSERT
   public function insert(): AbstractQueryBuilder {
      return new InsertQueryBuilder($this->connection, $this->table);
   }

   // Método para generar consultas UPDATE
   public function update(): AbstractQueryBuilder {
      return new UpdateQueryBuilder($this->connection, $this->table);
   }

   // Método para generar consultas DELETE
   public function delete(): AbstractQueryBuilder {
      return new DeleteQueryBuilder($this->connection, $this->table);
   }

}