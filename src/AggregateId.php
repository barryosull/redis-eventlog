<?php namespace ReventLog;

class AggregateId
{
    private $type;
    private $instance_id;

    public function __construct(string $type, string $instance_id)
    {
        $this->type = $type;
        $this->instance_id = $instance_id;
    }

    public function toString()
    {
        return $this->type."-".$this->instance_id;
    }
}