<?php
/**
 * @package     core/VQuery
 * @subpackage  Builders
 * @file        SelectQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 01:08:19
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Builders;
use Vertex\Core\VQuery\Managers\AbstractQueryBuilder;
use Vertex\Core\VQuery\Handlers\QueryResult;
class SelectQueryBuilder extends AbstractQueryBuilder {
   private array $select = ['*'];
   private array $where = [];
   private array $join = [];
   private array $orderBy = [];
   private array $groupBy = [];
   private int $limit = 0;
   private int $offset = 0;

   public function select(array $columns = []): self {
      $this->select = empty($columns) ? ['*'] : $columns;
      return $this;
   }

   public function where(array $conditions): self {
      $this->where = $conditions;
      return $this;
   }

   public function join(string $table, string $on): self {
      $this->join[] = "JOIN {$table} ON {$on}";
      return $this;
   }

   public function orderBy(string $column, string $direction = 'ASC'): self {
      $this->orderBy[] = "{$column} {$direction}";
      return $this;
   }

   public function groupBy(string $column): self {
      $this->groupBy[] = $column;
      return $this;
   }

   public function paginate(int $page, int $perPage): self {
      $this->limit = $perPage;
      $this->offset = ($page - 1) * $perPage;
      return $this;
   }

   public function build(): string {
      $sql = "SELECT " . implode(', ', $this->select) . " FROM {$this->table}";
      if ($this->join)
         $sql .= ' ' . implode(' ', $this->join);
      $sql .= $this->buildWhere($this->where);
      if ($this->orderBy)
         $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
      if ($this->limit)
         $sql .= " LIMIT {$this->limit} OFFSET {$this->offset}";
      return $sql;
   }

   public function execute(): QueryResult {
      $sql = $this->build();
      return $this->executeQuery($sql);
   }
}