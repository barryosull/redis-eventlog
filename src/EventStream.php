<?php namespace ReventLog;

interface EventStream
{
    public function next();
}