<?php

declare(strict_types=1);

use App\Application\Actions\Tasks\AddTaskAction;
use App\Application\Actions\Tasks\ListTaskAction;
use App\Application\Actions\Tasks\UpdateTaskAction;
use App\Application\Actions\Tasks\ViewTaskAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
  $app->options('/{routes:.*}', function (Request $request, Response $response) {
    // CORS Pre-Flight OPTIONS Request Handler
    return $response;
  });

  $app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello xD');
    return $response;
  });

  $app->group('/api/v1', function(Group $group) {
    $group->group('/tasks', function(Group $group) {
      $group->get('', ListTaskAction::class);
      $group->get('/{id}', ViewTaskAction::class);
      $group->post('', AddTaskAction::class);
      $group->put('/{id}', UpdateTaskAction::class);
    });
  });
};
