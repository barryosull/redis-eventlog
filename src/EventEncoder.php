<?php namespace ReventLog;

class EventEncoder
{
    public function encode(array $events): array
    {
        return array_map(function($event){
            return serialize($event);
        }, $events);
    }

    public function decode(array $encoded_event): array
    {
        return array_map(function($event){
            return unserialize($event);
        }, $encoded_event);
    }
}