<?php namespace ReventLogTests\Integration;

use ReventLog;
use ReventLogTests\Fakes;

abstract class ReventLogTest extends \PHPUnit\Framework\TestCase
{
    const START_POSITION = 0;

    /** @var \ReventLog\Type\RedisList\ReventLog $log */
    protected $log;
    protected $events;

    public function setUp()
    {
        $this->log = $this->eventLog();
        $this->log->clear();

        $this->events = [
            new Fakes\TestStarted(),
            new Fakes\TestRunning(),
            new Fakes\TestCompleted()
        ];
    }

    abstract public function eventLog(): ReventLog\EventLog;

    public function test_can_add_events_to_log()
    {
        $this->log->append($this->events);

        $stream = $this->log->getStream(self::START_POSITION);

        $this->assertStreamHasEvents($this->events, $stream);
    }

    private function assertStreamHasEvents($expected, ReventLog\EventStream $stream)
    {
        $actual = [];
        while (($event = $stream->next()) !== null) {
            $actual[] = $event;
        }

        $this->assertEquals($expected, $actual);
    }

    public function test_reading_from_stream()
    {
        $this->log->append($this->events);
        $this->log->append($this->events);

        $stream = $this->log->getStream(self::START_POSITION);

        $this->assertStreamHasEvents(array_merge($this->events, $this->events), $stream);
    }

    public function test_picking_up_from_position_in_log()
    {
        $this->log->append($this->events);
        $this->log->append($this->events);

        $last_position = 3;
        $stream_from_position = $this->log->getStream($last_position);
        $this->assertStreamHasEvents($this->events, $stream_from_position); // TODO: Bring more clarity to "events" concept
    }

    public function test_getting_the_latest_event()
    {
        $event = $this->log->latestEvent();
        $this->assertTrue(is_null($event), "Fetching an event from an empty log should return null");

        $this->log->append($this->events);
        $actual = $this->log->latestEvent();

        $expected = $this->events[2];
        $this->assertEquals($expected, $actual, "The events do not match");
    }
}