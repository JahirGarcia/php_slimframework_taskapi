<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

abstract class MySqlRepository {

  /**
   * @var string
   */
  private $user;

  /**
   * @var string
   */
  private $password;

  public function __construct() {
    $this->user = $_ENV['MYSQL_DB_USER'];
    $this->password = $_ENV['MYSQL_DB_PASSWORD'];
  }

  /**
   * @return string
   */
  protected function connectionString() : string {
    return $_ENV['MYSQL_DB_DSN'];
  }

  /**
   * @return string
   */
  protected function user() : string {
    return $this->user;
  }

  /**
   * @return string
   */
  protected function password() : string {
    return $this->password;
  }

}
