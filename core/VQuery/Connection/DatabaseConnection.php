<?php
/**
 * @package     core/VQuery
 * @subpackage  Connection
 * @file        DatabaseConnection
 * @author      Fernando Castillo <nando.castillo@outlook.com>
 * @date        2024-11-14 00:22:09
 * @version     1.0.0
 * @description
 */
declare(strict_types=1);

namespace Vertex\Core\VQuery\Connection;

use PDO;
use PDOException;
use Vertex\Core\Handler\Config;
use Vertex\Core\VQuery\Exceptions\UnsupportedDriverException;
use Vertex\Core\VQuery\Exceptions\DatabaseConnectionException;

class DatabaseConnection {
   private ?PDO $connection = null;
   private string $driver;
   private string $host;
   private int $port;
   private string $dbname;
   private string $user;
   private string $password;
   private string $charset;
   private array $options;
   // Pool de conexiones estáticas
   private static array $connectionPool = [];

   public function __construct(string $type = 'default') {
      $cfg = new Config();
      $path = "db.{$type}";
      $this->driver = $cfg->get("{$path}.driver");
      $this->host = $cfg->get("{$path}.host");
      $this->port = $cfg->get("{$path}.port");
      $this->dbname = $cfg->get("{$path}.database");
      $this->user = $cfg->get("{$path}.username");
      $this->password = $cfg->get("{$path}.password");
      $this->charset = $cfg->get("{$path}.charset");
      $this->options = $cfg->get("{$path}.options");
   }

   private function connect(): void {
      $dsn = $this->getDsn();
      try {
         if (isset(self::$connectionPool[$dsn])) {
            $this->connection = self::$connectionPool[$dsn];
         } else {
            $this->connection = new PDO($dsn, $this->user, $this->password, $this->options);
            self::$connectionPool[$dsn] = $this->connection; // Guardar la conexión en el pool
         }
      } catch (PDOException $e) {
         throw new DatabaseConnectionException("Error connecting to database: {$e->getMessage()}", $e->getCode());
      }
   }

   private function getDSN(): string {
      return match ($this->driver) {
         'mysql' => "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}",
         'pgsql' => "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};user={$this->user};password={$this->password}",
         'sqlite' => "sqlite:{$this->dbname}",
         'sqlsrv' => "sqlsrv:Server={$this->host},{$this->port};Database={$this->dbname}",
         default => throw new UnsupportedDriverException("Unsupported driver: {$this->driver}")
      };
   }

   public function getConnection(): PDO {
      if ($this->connection === null) {
         $this->connect();
      }
      return $this->connection;
   }

   // Cerrar la conexión (si es necesario)
   public function close(): void {
      $this->connection = null;
   }
}