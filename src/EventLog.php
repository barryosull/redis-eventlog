<?php namespace ReventLog;

interface EventLog
{
    public function append(AggregateId $aggregate_id, array $events);

    public function getAggregateStream(AggregateId $aggregate_id): EventStream;

    public function getStream(string $last_position): EventStream;
}