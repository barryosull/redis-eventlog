<?php namespace ReventLogTests\Integration\Infrastructure;

use Predis;
use ReventLog\Type\RedisList\ReventLog;
use ReventLog\EventLog;
use ReventLogTests\Integration\ReventLogTest;

class ReventLogRedisListTest extends ReventLogTest
{
    public function eventLog(): EventLog
    {
        $client = new Predis\Client();
        return new ReventLog($client);
    }

    public function test_waits_for_events()
    {
        if ($child_PID = pcntl_fork()) {
            $callable = (object)['saw_event'=>false];
            $this->log->subscribe(function() use ($callable) {
                $callable->saw_event = true;
            });

            $this->assertTrue($callable->saw_event);

            posix_kill($child_PID, SIGKILL);

        } else {
            $this->log->append($this->events);
            sleep(1);
            exit(0);
        }
    }
}