# Redis EventLog

A simple event log implemented in Redis. Currently uses a Redis List implementation.

## Usage
```php
// Create the EventLog
$client = new Predis\Client();
$event_log = new ReventLog\Type\RedisList\ReventLog($client);
...
// Append events
$event_log->append($events);
...
// Fetch a stream from a position
$stream = $event->getStream($last_position);

// Iterate through the stream
while (($event = $stream->next() !== null) {
    // Do something with $event
}

```