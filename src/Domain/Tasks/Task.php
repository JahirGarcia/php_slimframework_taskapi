<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Task implements JsonSerializable {

  /**
   * @var string
   */
  private $id;

  /**
   * @var string
   */
  private $description;

  /**
   * @var bool
   */
  private $completed;


  /**
   * @param string  $id
   * @param string  $description
   * @param bool    $completed
   */
  public function __construct(string $id, string $description, bool $completed) {
    $this->id = $id;
    $this->description = $description;
    $this->completed = $completed;
  }

  /**
   * @param string
   * @return self
   */
  public static function create(string $description) : self {
    $id = Uuid::uuid4()->toString();
    $completed = false;
    return new self($id, $description, $completed);
  }

  /**
   * @return string
   */
  public function id() : string {
    return $this->id;
  }

  /**
   * @return string
   */
  public function description() : string {
    return $this->description;
  }

  /**
   * @param string
   */
  public function changeDescription(string $description) : void {
    $this->description = $description;
  }

  /**
   * @return bool
   */
  public function completed() : bool {
    return $this->completed;
  }

  public function complete() : void {
    $this->completed = true;
  }

  /**
   * @return array
   */
  public function jsonSerialize() {
    return [
      'id' => $this->id(),
      'description' => $this->description(),
      'completed' => $this->completed()
    ];
  }

}
