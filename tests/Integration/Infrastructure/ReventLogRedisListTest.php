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
}