<?php namespace ReventLog;

class ReventLog
{
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function append(AggregateId $aggregate_id, array $events)
    {
        $key = $aggregate_id->toString();
        if (!isset($this->events[$key])) {
            $this->events[$aggregate_id->toString()] = [];
        }
        $this->events[$key] = array_merge($this->events[$aggregate_id->toString()], $events);
    }

    public function getAggregateStream(AggregateId $aggregate_id): EventStream
    {
        $key = $aggregate_id->toString();
        if (!isset($this->events[$key])) {
            return new EventStream([]);
        }
        return new EventStream($this->events[$key]);
    }

    public function getStream(string $last_position): EventStream
    {
        $events = [];
        foreach ($this->events as $aggregate_events) {
            $events = array_merge($events, $aggregate_events);
        }

        if ($last_position) {
            $events = array_slice($events, $last_position);
        }

        return new EventStream($events);
    }
}
