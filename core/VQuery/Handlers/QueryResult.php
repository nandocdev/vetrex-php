<?php
/**
 * @package     core/VQuery
 * @subpackage  Handlers
 * @file        QueryResult
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:32:40
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Handlers;

class QueryResult {
   private bool $success;
   private mixed $data;
   private ?string $errorMessage;

   public function __construct(bool $success, mixed $data = null, ?string $errorMessage = null) {
      $this->success = $success;
      $this->data = $data;
      $this->errorMessage = $errorMessage;
   }

   // Métodos getter
   public function isSuccess(): bool {
      return $this->success;
   }

   public function getData(): mixed {
      return $this->data;
   }

   public function getRows(): int {
      return count($this->data);
   }

   public function getErrorMessage(): ?string {
      return $this->errorMessage;
   }
}