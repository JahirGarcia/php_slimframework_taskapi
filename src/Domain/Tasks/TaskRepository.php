<?php
declare(strict_types=1);

namespace App\Domain\Tasks;

use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskNotFoundException;

interface TaskRepository {

  /**
   * @param Task $task
   * @return Task
   */
  public function add(Task $task) : Task;

  /**
   * @return Task[]
   */
  public function findAll() : array;

  /**
   * @param string $id
   * @return Task
   * @throws TaskNotFoundException
   */
  public function findById(string $id) : Task;

}
