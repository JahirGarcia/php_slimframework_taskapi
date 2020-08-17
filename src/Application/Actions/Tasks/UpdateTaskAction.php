<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Application\Actions\Tasks\TaskAction;
use App\Domain\Tasks\Task;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateTaskAction extends TaskAction {

  public function action() : Response {
    // getFormData should called only once
    $body = $this->getFormData();

    $id = $this->resolveArg('id');
    $description = $body->description;
    $completed = $body->completed;

    $task = new Task($id, $description, $completed);
    $this->taskRepository->update($task);

    $this->logger->info("Task with id `${id}` was updated.");

    return $this->respondWithData($task);
  }

}
