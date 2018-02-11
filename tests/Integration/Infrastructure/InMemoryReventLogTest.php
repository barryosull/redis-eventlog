<?php namespace ReventLogTests\Integration\Infrastructure;

use ReventLog\ReventLog;
use ReventLogTests\Integration\ReventLogTest;

class InMemoryReventLogTest extends ReventLogTest
{
    public function eventLog(): ReventLog
    {
        return new ReventLog();
    }
}