<?php namespace ReventLog;

interface EventLog
{
    public function clear();

    public function append(array $events);

    public function getStream(string $last_position): EventStream;

    public function latestEvent();

    public function subscribe(callable $on_event);
}