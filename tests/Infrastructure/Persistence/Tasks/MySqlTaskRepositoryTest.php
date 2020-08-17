<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Tasks;

use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskNotFoundException;
use App\Infrastructure\Persistence\Tasks\MySqlTaskRepository;
use Tests\TestCase;

class MySqlTaskRepositoryTest extends TestCase {

  private function defaultTaskStored() {
    return new Task(
      '3b74bc15-e5ea-409a-8ac9-a9e437e05520', 
      'Go for the kids to the school.', 
      false
    );
  }

  public function testFindAll() {

    $expectedTasks = [ $this->defaultTaskStored() ];

    $taskRepository = new MySqlTaskRepository();
    $tasks = $taskRepository->findAll();

    $this->assertEquals($expectedTasks, $tasks);

  }

  public function testAdd() {
    $task = Task::create('I have a video confference to the 10:00 am.');

    $taskRepository = new MySqlTaskRepository();
    $storedTask = $taskRepository->add($task);

    $this->assertEquals($task, $storedTask);
  }

  public function testFindById() {

    $defaultTaskStored = $this->defaultTaskStored();

    $taskRepository = new MySqlTaskRepository();
    $task = $taskRepository->findById($defaultTaskStored->id());

    $this->assertEquals($defaultTaskStored, $task);
  }

  public function testFindByIdThrowsTaskNotFoundException() {

    $taskRepository = new MySqlTaskRepository();
    $this->expectException(TaskNotFoundException::class);
    $taskRepository->findById('Invalid ID');

  }

  public function testUpdate() {
    $defaultTaskStored = $this->defaultTaskStored();
    $defaultTaskStored->changeDescription('Watch Milan match.');
    $defaultTaskStored->complete();

    $taskRepository = new MySqlTaskRepository();
    $task = $taskRepository->update($defaultTaskStored);

    $this->assertEquals($defaultTaskStored, $task);
  }
  
}
