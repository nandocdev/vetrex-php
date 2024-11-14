<?php
/**
 * @package     core/VQuery
 * @subpackage  Builders
 * @file        DeleteQueryBuilder
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 01:07:50
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Builders;
use Vertex\Core\VQuery\Managers\AbstractQueryBuilder;
use Vertex\Core\VQuery\Handlers\QueryResult;
class DeleteQueryBuilder extends AbstractQueryBuilder {
   private array $where = [];

   public function where(array $conditions): self {
      $this->where = $conditions;
      return $this;
   }

   public function build(): string {
      $whereClause = $this->buildWhere($this->where);
      return "DELETE FROM {$this->table} {$whereClause}";
   }

   public function execute(): QueryResult {
      $sql = $this->build();
      return $this->executeQuery($sql);
   }
}