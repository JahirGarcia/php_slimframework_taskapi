<?php

declare(strict_types=1);

namespace App\Application\Actions\Tasks;

use App\Application\Actions\ActionPayload;
use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskRepository;
use DI\Container;
use Slim\Psr7\Factory\StreamFactory;
use Tests\TestCase;

class UpdateTaskActionTest extends TestCase
{

  public function testAction()
  {
    $app = $this->getAppInstance();

    /** @var Container $container */
    $container = $app->getContainer();

    $task = new Task(
      '3b74bc15-e5ea-409a-8ac9-a9e437e05520',
      'Watch Milan match.',
      true
    );

    $taskRepositoryProphecy = $this->prophesize(TaskRepository::class);
    $taskRepositoryProphecy
      ->update($task)
      ->willReturn($task)
      ->shouldBeCalledOnce();

    $container->set(TaskRepository::class, $taskRepositoryProphecy->reveal());

    $streamFactory = new StreamFactory();
    $request = $this->createRequest('PUT', "/api/v1/tasks/{$task->id()}")
                ->withHeader('Content-Type', 'application/json')
                ->withBody(
                  $streamFactory->createStream(
                    json_encode([
                      'description' => $task->description(),
                      'completed' => $task->completed()
                    ])
                  )
                );
    $response = $app->handle($request);

    $payload = (string) $response->getBody();
    $expectedPayload = new ActionPayload(200, $task);
    $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

    $this->assertEquals($serializedPayload, $payload);
  }
}
