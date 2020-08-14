<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Application\Actions\Tasks\TaskAction;
use Psr\Http\Message\ResponseInterface as Response;

class ListTaskAction extends TaskAction {

  /**
   * {@inheritdoc}
   */
  protected function action() : Response {

    $tasks = $this->taskRepository->findAll();

    $this->logger->info('Task list was viewed.');

    return $this->respondWithData($tasks);
  }

}
