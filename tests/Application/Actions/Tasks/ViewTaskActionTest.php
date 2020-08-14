<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Tasks;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Tasks\Task;
use App\Domain\Tasks\TaskNotFoundException;
use App\Domain\Tasks\TaskRepository;
use DI\Container;
use Slim\Handlers\ErrorHandler;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewTaskActionTest extends TestCase {

  private function getDefaultTask() {
    return new Task(
      '3b74bc15-e5ea-409a-8ac9-a9e437e05520', 
      'Go for the kids to the school.', 
      false
    );
  }
  
  public function testAction() {
    $app = $this->getAppInstance();

    /** @var Container $container */
    $container = $app->getContainer();
    
    $task = $this->getDefaultTask();

    $taskRepositoryProphecy = $this->prophesize(TaskRepository::class);
    $taskRepositoryProphecy
      ->findById($task->id())
      ->willReturn($task)
      ->shouldBeCalledOnce();

    $container->set(TaskRepository::class, $taskRepositoryProphecy->reveal());

    $request = $this->createRequest('GET', "/api/v1/tasks/{$task->id()}");
    $response = $app->handle($request);

    $payload = (string) $response->getBody();
    $expectedPayload = new ActionPayload(200, $task);
    $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

    $this->assertEquals($serializedPayload, $payload);
  }

  public function testActionThrowsUserNotFoundException() {
    $app = $this->getAppInstance();

    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
    $errorMiddleware->setDefaultErrorHandler($errorHandler);

    $app->add($errorMiddleware);

    /** @var Container $container */
    $container = $app->getContainer();

    $task = $this->getDefaultTask();

    $taskRepositoryProphecy = $this->prophesize(TaskRepository::class);
    $taskRepositoryProphecy
      ->findById($task->id())
      ->willThrow(new TaskNotFoundException())
      ->shouldBeCalledOnce();

    $container->set(TaskRepository::class, $taskRepositoryProphecy->reveal());

    $request = $this->createRequest('GET', "/api/v1/tasks/{$task->id()}");
    $response = $app->handle($request);

    $payload = (string) $response->getBody();
    $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The task you requested does not exist.');
    $expectedPayload = new ActionPayload(404, null, $expectedError);
    $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

    $this->assertEquals($serializedPayload, $payload);
  }

}
