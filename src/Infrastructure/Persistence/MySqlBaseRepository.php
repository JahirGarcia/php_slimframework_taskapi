<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;
use PDOException;
use App\Infrastructure\Persistence\MySqlRepository;

abstract class MySqlBaseRepository extends MySqlRepository {

  /**
   * @var PDO
   * @throws PDOException
   */
  private $connection;

  public function __construct() {
    parent::__construct();

    $this->connection = new PDO(
      $this->connectionString(),
      $this->user(),
      $this->password()
    );
  }

  /**
   * @return PDO
   */
  protected function getConnection() {
    return $this->connection;
  }

}
