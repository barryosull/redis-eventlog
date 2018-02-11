<?php namespace ReventLog;

use Predis;

// TODO: Enable write to disk

class ReventLog implements EventLog
{
    const STORE = 'event_log';

    private $client;

    public function __construct(Predis\Client $client)
    {
        $this->client = $client;
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
        $encoded_events = $this->encodeEvents($events);
        $this->client->rpush(self::STORE, $encoded_events);
    }

    private function encodeEvents(array $events)
    {
        return array_map(function($event){
            return serialize($event);
        }, $events);
    }

    public function getStream(string $last_position): EventStream
    {
        return new ReventStream($this->client, $last_position);
    }
}
