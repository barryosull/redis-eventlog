<?php namespace ReventLog\Type\RedisPubSub;

use Predis;
use ReventLog\EventLog;
use ReventLog\EventStream;
use ReventLog\EventEncoder;

// TODO: Enable write to disk
class ReventLog implements EventLog
{
    const STORE = 'event_log';

    private $client;
    private $encoder;

    public function __construct(Predis\Client $client)
    {
        $this->client = $client;
        $this->encoder = new EventEncoder();
    }

    /**
     * NB: Only used when testing, do not use in production, it will clear everything
     */
    public function clear()
    {
        $this->client->del([self::STORE]);
    }

    public function append(array $events)
    {
        $encoded_events = $this->encoder->encode($events);
        $this->client->rpush(self::STORE, $encoded_events);
    }

    public function getStream(string $last_position): EventStream
    {
        return new ReventStream($this->client, $this->encoder, $last_position);
    }
}