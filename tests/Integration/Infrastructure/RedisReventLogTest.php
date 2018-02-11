<?php namespace ReventLogTests\Integration\Infrastructure;

use Predis;
use ReventLog\ReventLog;
use ReventLog\EventLog;
use ReventLogTests\Integration\ReventLogTest;

class RedisReventLogTest extends ReventLogTest
{
    public function eventLog(): EventLog
    {
        $client = new Predis\Client();
        return new ReventLog($client);
    }
}