<?php
declare(strict_types=1);

namespace Tests\Domain\Tasks;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use App\Domain\Tasks\Task;

class TaskTest extends TestCase {

  public function taskProvider() {
    return [
      [Uuid::uuid4()->toString(), 'I need to learn PHP in this summer.', true],
      [Uuid::uuid4()->toString(), 'Dont forget study for the math test.', false],
      [Uuid::uuid4()->toString(), 'Buy the newspaper.', true]
    ];
  }

  /**
   * @dataProvider taskProvider
   * @param $description
   */
  public function testNamedContructor($description) {
    $task = Task::create($description);

    $this->assertEquals($description, $task->description());
    $this->assertEquals(false, $task->completed());
  }

  /**
   * @dataProvider taskProvider
   * @param $id
   * @param $description
   * @param $completed
   */
  public function testGetters($id, $description, $completed) {
    $task = new Task($id, $description, $completed);

    $this->assertEquals($id, $task->id());
    $this->assertEquals($description, $task->description());
    $this->assertEquals($completed, $task->completed());
  }

  /**
   * @dataProvider taskProvider
   * @param $id
   * @param $description
   * @param $completed
   */
  public function testSetters($id, $description, $completed) {
    $task = new Task($id, $description, $completed);

    $newDescription = 'Go for the kids to the school.';
    $task->changeDescription($newDescription);
    $task->complete();

    $this->assertEquals($newDescription, $task->description());
    $this->assertEquals(true, $task->completed());
  }

  /**
   * @dataProvider taskProvider
   * @param $id
   * @param $description
   * @param $completed
   */
  public function testJsonSerialize($id, $description, $completed) {
    $task = new Task($id, $description, $completed);

    $expectedPayload = json_encode([
      'id' => $id,
      'description' => $description,
      'completed' => $completed
    ]);

    $this->assertEquals($expectedPayload, json_encode($task));
  }

}
