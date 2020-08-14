<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Domain\Tasks\Task;
use App\Application\Actions\Tasks\TaskAction;
use Psr\Http\Message\ResponseInterface as Response;

class AddTaskAction extends TaskAction {

  /**
   * {@inheritdoc}
   */
  protected function action(): Response {

    $description = $this->getFormData()->description;
    $task = Task::create($description);
    $this->taskRepository->add($task);

    $taskId = $task->id();
    $this->logger->info("Task with id `${taskId}` was stored.");
    
    return $this->respondWithData($task);
  }

}
