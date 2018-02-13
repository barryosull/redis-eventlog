<?php namespace ReventLogTests\Integration\Infrastructure;

use ReventLog\EventLog;
use ReventLogTests\Fakes\InMemoryEventLog;
use ReventLogTests\Integration\ReventLogTest;

class ReventLogInMemoryTest extends ReventLogTest
{
    public function eventLog(): EventLog
    {
        return new InMemoryEventLog;
    }

    public function test_waits_for_events()
    {
        $callable = (object)['saw_event'=>false];
        $this->log->subscribe(function() use ($callable) {
            $callable->saw_event = true;
        });

        $this->log->append($this->events);

        $this->assertTrue($callable->saw_event);
    }
}