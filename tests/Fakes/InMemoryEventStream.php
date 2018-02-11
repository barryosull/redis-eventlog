<?php namespace ReventLogTests\Fakes;

use ReventLog\EventStream;

class InMemoryEventStream implements EventStream
{
    private $events;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function next()
    {
        return array_shift($this->events);
    }
}