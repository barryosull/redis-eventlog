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
}