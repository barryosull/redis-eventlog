<?php namespace ReventLogTests\Integration;

use ReventLog;
use ReventLogTests\Fakes;

abstract class ReventLogTest extends \PHPUnit\Framework\TestCase
{
    const AGGREGATE_TYPE = 'test.aggregate';
    const AGGREGATE_ID = 'c51a2240-4f4f-4cb9-b5af-53954e039b28';

    /** @var ReventLog\ReventLog $log */
    private $log;
    private $events;

    public function setUp()
    {
        $this->log = $this->eventLog();

        $this->events = [
            new Fakes\TestStarted(),
            new Fakes\TestRunning(),
            new Fakes\TestCompleted()
        ];
    }

    abstract public function eventLog(): ReventLog\ReventLog;

    public function test_can_add_events_to_log()
    {
        $aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::AGGREGATE_ID);

        $this->log->append($aggregate_id, $this->events);

        $stream = $this->log->getAggregateStream($aggregate_id);

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

    const OTHER_AGGREGATE_ID = '9291ce1c-eb17-4ba9-a519-989c32ca9014';

    public function test_aggregate_streams_are_unique_to_an_aggregate()
    {
        $aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::AGGREGATE_ID);

        $other_aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::OTHER_AGGREGATE_ID);

        $this->log->append($aggregate_id, $this->events);

        $stream = $this->log->getAggregateStream($other_aggregate_id);

        $this->assertStreamHasEvents([], $stream);
    }

    public function test_reading_from_aggregated_stream()
    {
        $aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::AGGREGATE_ID);
        $other_aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::OTHER_AGGREGATE_ID);
        $this->log->append($aggregate_id, $this->events);
        $this->log->append($other_aggregate_id, $this->events);

        $stream = $this->log->getStream("");

        $this->assertStreamHasEvents(array_merge($this->events, $this->events), $stream);
    }

    public function test_picking_up_from_position_in_log()
    {
        $aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::AGGREGATE_ID);
        $other_aggregate_id = new ReventLog\AggregateId(self::AGGREGATE_TYPE, self::OTHER_AGGREGATE_ID);

        $this->log->append($aggregate_id, $this->events);
        $this->log->append($other_aggregate_id, $this->events);

        $last_position = 3;
        $stream_from_position = $this->log->getStream($last_position);
        $this->assertStreamHasEvents($this->events, $stream_from_position); // TODO: Bring more clarity to "events" concept
    }
}