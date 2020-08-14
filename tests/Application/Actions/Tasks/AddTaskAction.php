<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Tasks;

use App\Application\Actions\ActionPayload;
use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskRepository;
use DI\Container;
use Tests\TestCase;

class AddTaskActionTest extends TestCase {
  
  public function testAction() {
    $app = $this->getAppInstance();

    /** @var Container $container */
    $container = $app->getContainer();
    
    $task = new Task(
      '3b74bc15-e5ea-409a-8ac9-a9e437e05520', 
      'Go for the kids to the school.', 
      false
    );

    $taskRepositoryProphecy = $this->prophesize(TaskRepository::class);
    $taskRepositoryProphecy
      ->add($task->description())
      ->willReturn(true)
      ->shouldBeCalledOnce();

    $container->set(TaskRepository::class, $taskRepositoryProphecy->reveal());

    $request = $this->createRequest('POST', '/api/v1/tasks');
    $request->withAddedHeader('Content-Type', 'application/json');
    $request->getBody()->write(json_encode([
      'description' => $task->description()
    ]));
    $response = $app->handle($request);

    $payload = (string) $response->getBody();
    $expectedPayload = new ActionPayload(200, $task);
    $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

    $this->assertEquals($serializedPayload, $payload);
  }

}
