<?php
/**
 * @package     core/VQuery
 * @subpackage  Builders
 * @file        InsertQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 01:08:10
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Builders;
use Vertex\Core\VQuery\Managers\AbstractQueryBuilder;
use Vertex\Core\VQuery\Handlers\QueryResult;

class InsertQueryBuilder extends AbstractQueryBuilder {
   private array $columns = [];
   private array $values = [];

   public function insert(array $columns, array $values): self {
      if (count($columns) !== count($values)) {
         throw new \InvalidArgumentException('Columns and values must have the same number of elements');
      }

      $this->columns = $columns;
      $this->values = $values;
      return $this;
   }

   public function build(): string {
      $columns = implode(', ', $this->columns);
      $placeholders = implode(', ', array_fill(0, count($this->values), '?'));
      $this->params = $this->values;
      return "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
   }

   public function execute(): QueryResult {
      $sql = $this->build();
      return $this->executeQuery($sql);
   }
}