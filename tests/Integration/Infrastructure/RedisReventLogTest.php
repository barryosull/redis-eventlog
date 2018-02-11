<?php namespace ReventLogTests\Integration\Infrastructure;

use ReventLog\ReventLog;
use ReventLog\EventLog;
use ReventLogTests\Integration\ReventLogTest;

class RedisReventLogTest extends ReventLogTest
{
    public function eventLog(): EventLog
    {
        return new ReventLog();
    }
}