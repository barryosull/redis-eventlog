<?php namespace ReventLogTests\Fakes;

use ReventLog\EventLog;
use ReventLog\EventStream;

class InMemoryEventLog implements EventLog
{
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function append(array $events)
    {
        $this->events = array_merge($this->events, $events);
    }

    public function getStream(string $last_position): EventStream
    {
        $events = $this->events;

        if ($last_position) {
            $events = array_slice($this->events, $last_position);
        }

        return new InMemoryEventStream($events);
    }

    public function clear()
    {
        $this->events = [];
    }

    public function latestEvent()
    {
        return array_values(array_slice($this->events, -1))[0] ?? null;
    }
}