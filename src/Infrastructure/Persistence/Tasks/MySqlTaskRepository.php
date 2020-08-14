<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tasks;

use PDO;
use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskNotFoundException;
use App\Domain\Tasks\TaskRepository;
use App\Infrastructure\Persistence\MySqlBaseRepository;
use PDOException;

class MySqlTaskRepository extends MySqlBaseRepository implements TaskRepository {

  /**
   * {@inheritdoc}
   */
  public function add(Task $task): Task {
    $db = $this->getConnection();
    $sql = 'insert into tasks (id, description, completed) values (:id, :description, :completed)';
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $task->id());
    $statement->bindValue(':description', $task->description());
    $statement->bindValue(':completed', $task->completed());
    $success = $statement->execute();

    if(!$success) throw new PDOException();

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
    $success = $statement->execute();

    if(!$success) throw new PDOException();

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
    $success = $statement->execute();

    if(!$success) throw new PDOException();

    $storedTask = $statement->fetch(PDO::FETCH_OBJ);

    if(!$storedTask) throw new TaskNotFoundException();

    $task = new Task(
      $storedTask->id,
      $storedTask->description,
      (bool)$storedTask->completed
    );

    return $task;
  }

}
