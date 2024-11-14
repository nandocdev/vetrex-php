<?php
/**
 * @package     core/VQuery
 * @subpackage  Builders
 * @file        UpdateQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 01:08:28
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Builders;
use Vertex\Core\VQuery\Managers\AbstractQueryBuilder;
use Vertex\Core\VQuery\Handlers\QueryResult;

class UpdateQueryBuilder extends AbstractQueryBuilder {
   private array $set = [];
   private array $where = [];

   public function set(string $column, $value): self {
      $this->set[] = "{$column} = ?";
      $this->params[] = $value;
      return $this;
   }

   public function where(array $conditions): self {
      $this->where = $conditions;
      return $this;
   }

   public function build(): string {
      $setClause = implode(', ', $this->set);
      $whereClause = $this->buildWhere($this->where);
      return "UPDATE {$this->table} SET {$setClause} {$whereClause}";
   }

   public function execute(): QueryResult {
      $sql = $this->build();
      return $this->executeQuery($sql);
   }
}