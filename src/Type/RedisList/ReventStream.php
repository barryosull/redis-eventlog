<?php namespace ReventLog\Type\RedisList;

use Predis;
use ReventLog\EventStream;
use ReventLog\EventEncoder;

class ReventStream implements EventStream
{
    private $client;
    private $encoder;
    private $last_position;
    private $events;

    const CHUNK_SIZE = 2;

    public function __construct(Predis\Client $client, EventEncoder $encoder, int $last_position)
    {
        $this->client = $client;
        $this->encoder = $encoder;
        $this->last_position = $last_position;
        $this->events = [];
    }

    public function next()
    {
        if ($this->noEvents()){
            $this->loadEvents();
        }
        return $this->getEventAndIncrementPosition();
    }

    private function noEvents(): bool
    {
        return count($this->events) == 0;
    }

    private function loadEvents()
    {
        $start = $this->last_position;
        $stop = $start + self::CHUNK_SIZE;
        $encoded_events = $this->client->lrange(ReventLog::STORE, $start, $stop);
        $this->events = $this->encoder->decode($encoded_events);
    }

    private function getEventAndIncrementPosition()
    {
        $event = array_shift($this->events);
        if (!$event) {
            return null;
        }
        $this->last_position++;

        return $event;
    }
}