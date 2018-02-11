<?php namespace ReventLog;

class ReventLog implements EventLog
{
    public function __construct()
    {

    }

    public function append(AggregateId $aggregate_id, array $events)
    {

    }

    public function getAggregateStream(AggregateId $aggregate_id): EventStream
    {
        return new EventStream([]);
    }

    public function getStream(string $last_position): EventStream
    {
        return new EventStream([]);
    }
}
