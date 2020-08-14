<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Application\Actions\Tasks\TaskAction;
use Psr\Http\Message\ResponseInterface as Response;

class ViewTaskAction extends TaskAction {

  /**
   * {@inheritdoc}
   */
  protected function action() : Response {

    $id = (string) $this->resolveArg('id');
    $task = $this->taskRepository->findById($id);

    $this->logger->info("Task with id `${id}` was viewed.");

    return $this->respondWithData($task);
  }

}
