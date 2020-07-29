<?php namespace CodeIgniter;

use CodeIgniter\Test\ControllerTester;

class TestControllerTask extends  \CodeIgniter\Test\CIUnitTestCase

{
    //use ControllerTester;

    public function testShowTest()
    {
        $result = $this->withoutURI('http://localhost:8080/task')
                       ->controller(\App\Controllers\Task::class)
                       ->execute('task');

        $this->assertTrue($result->isOK());
    }
}