<?php
/**
 * @package     core/VQuery
 * @subpackage  Utils
 * @file        SqlHelper
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:41:47
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Utils;

class SqlHelper {
   // Método genérico para construir cláusulas (WHERE, HAVING, etc.)
   private static function buildClause(string $clauseName, array $conditions, bool $isWhere = true): string {
      if (empty($conditions)) {
         return '';
      }

      $clauses = [];
      foreach ($conditions as $field => $condition) {
         if (is_array($condition)) {
            $operator = strtoupper($condition['operator']);
            $value = $condition['value'];

            switch ($operator) {
               case 'IN':
                  $clauses[] = "{$field} IN (" . implode(',', array_map('self::escapeValue', $value)) . ")";
                  break;
               case 'BETWEEN':
                  $clauses[] = "{$field} BETWEEN " . self::escapeValue($value[0]) . " AND " . self::escapeValue($value[1]);
                  break;
               case 'LIKE':
                  $clauses[] = "{$field} LIKE " . self::escapeValue($value);
                  break;
               default:
                  $clauses[] = "{$field} {$operator} " . self::escapeValue($value);
            }
         } else {
            $clauses[] = "{$field} = " . self::escapeValue($condition);
         }
      }

      return ($isWhere ? " WHERE " : " HAVING ") . implode(' AND ', $clauses);
   }

   // Construir cláusula WHERE con soporte para operadores como IN, LIKE, BETWEEN, etc.
   public static function buildWhere(array $conditions): string {
      return self::buildClause('WHERE', $conditions);
   }

   // Construir cláusula HAVING con soporte para operadores complejos
   public static function buildHaving(array $conditions): string {
      return self::buildClause('HAVING', $conditions, false);
   }

   // Construir cláusula ORDER BY
   public static function buildOrderBy(array $order): string {
      if (empty($order)) {
         return '';
      }

      $orderClauses = array_map(fn($field, $direction) => "{$field} {$direction}", array_keys($order), $order);
      return ' ORDER BY ' . implode(', ', $orderClauses);
   }

   // Construir cláusula LIMIT con OFFSET
   public static function buildLimit(int $limit, int $offset = 0): string {
      return " LIMIT {$limit} OFFSET {$offset}";
   }

   // Método para escapar valores de parámetros (protección contra inyección SQL)
   public static function escapeValue(mixed $value): string {
      if (is_string($value)) {
         return "'" . addslashes($value) . "'";
      } elseif (is_null($value)) {
         return 'NULL';
      } elseif (is_bool($value)) {
         return $value ? 'TRUE' : 'FALSE';
      }
      return (string) $value;
   }

   // Escapar identificadores (como nombres de columnas o tablas)
   public static function escapeIdentifier(string $identifier): string {
      return "`{$identifier}`"; // En caso de ser MySQL
   }

   // Construcción de JOINs
   public static function buildJoin(array $joins): string {
      $joinClauses = [];

      foreach ($joins as $join) {
         $type = strtoupper($join['type']);
         $table = self::escapeIdentifier($join['table']);
         $on = $join['on'];

         $joinClauses[] = "{$type} JOIN {$table} ON {$on}";
      }

      return implode(' ', $joinClauses);
   }
}