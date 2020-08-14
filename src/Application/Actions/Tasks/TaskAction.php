<?php
declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Domain\Tasks\TaskRepository;

abstract class TaskAction extends Action {

  /**
   * @var TaskRepository
   */
  protected $taskRepository;

  /**
   * @param LoggerInterface $logger
   * @param TaskRepository $taskRepository
   */
  public function __construct(LoggerInterface $logger, TaskRepository $taskRepository) {
    parent::__construct($logger);
    $this->taskRepository = $taskRepository;
  }

}
