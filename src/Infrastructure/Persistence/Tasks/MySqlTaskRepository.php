<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tasks;

use PDO;
use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskNotFoundException;
use App\Domain\Tasks\TaskRepository;
use App\Infrastructure\Persistence\MySqlBaseRepository;
use PDOException;
use PDOStatement;

class MySqlTaskRepository extends MySqlBaseRepository implements TaskRepository {

  /**
   * @param PDOStatement $statement
   * @throws PDOException
   */
  private function throwExceptionOnFailure(PDOStatement $statement) : void {
    $errorInfo = $statement->errorInfo();

    $sqlstateCode = $errorInfo[0];
    $errorCode = $errorInfo[1];
    $errorMessage = $errorInfo[2];

    if($sqlstateCode !== '00000') throw new PDOException($errorMessage, $errorCode);
  }

  /**
   * {@inheritdoc}
   */
  public function add(Task $task): Task {
    $db = $this->getConnection();
    $sql = 'insert into tasks (id, description, completed) values (:id, :description, :completed)';
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $task->id());
    $statement->bindValue(':description', $task->description());
    $statement->bindValue(':completed', (int) $task->completed());
    $statement->execute();

    $this->throwExceptionOnFailure($statement);

    return $task;
  }
  
  /**
   * {@inheritdoc}
   */
  public function findAll() : array {
    $tasks = [];

    $db = $this->getConnection();
    $sql = 'select * from tasks';
    $statement = $db->prepare($sql);
    $statement->execute();

    $this->throwExceptionOnFailure($statement);

    $rows = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach($rows as $obj) {
      $task = new Task($obj->id, $obj->description, (bool)$obj->completed);
      $tasks[] = $task;
    }

    return $tasks;
  }

  /**
   * {@inheritdoc}
   */
  public function findById(string $id) : Task {
    $db = $this->getConnection();
    $sql = 'select * from tasks where id = :id';
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $this->throwExceptionOnFailure($statement);

    $storedTask = $statement->fetch(PDO::FETCH_OBJ);

    if(!$storedTask) throw new TaskNotFoundException();

    $task = new Task(
      $storedTask->id,
      $storedTask->description,
      (bool)$storedTask->completed
    );

    return $task;
  }

  /**
   * {@inheritdoc}
   */
  public function update(Task $task): Task {
    $db = $this->getConnection();
    $sql = 'update tasks set description = :description, completed = :completed where id = :id';
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $task->id());
    $statement->bindValue(':description', $task->description());
    $statement->bindValue(':completed', (int) $task->completed());
    $statement->execute();

    $this->throwExceptionOnFailure($statement);

    return $task;
  }

}
