<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

abstract class MySqlRepository {

  /**
   * @var string
   */
  private $dbname;

  /**
   * @var string
   */
  private $host;

  /**
   * @var string
   */
  private $user;

  /**
   * @var string
   */
  private $password;

  public function __construct() {
    $this->dbname = 'taskapi';
    $this->host = '127.0.0.1';
    $this->user = 'root';
    $this->password = '';
  }

  /**
   * @return string
   */
  protected function connectionString() : string {
    return "mysql:dbname=$this->dbname;host:$this->host";
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
