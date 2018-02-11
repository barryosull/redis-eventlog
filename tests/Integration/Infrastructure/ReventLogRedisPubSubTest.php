<?php namespace ReventLogTests\Integration\Infrastructure;

use Predis;
use ReventLog\Type\RedisPubSub\ReventLog;
use ReventLog\EventLog;
use ReventLogTests\Integration\ReventLogTest;

class ReventLogRedisPubSubTest extends ReventLogTest
{
    public function eventLog(): EventLog
    {
        $client = new Predis\Client();
        return new ReventLog($client);
    }
}