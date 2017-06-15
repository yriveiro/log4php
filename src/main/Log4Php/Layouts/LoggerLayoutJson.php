<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutJson extends LoggerLayout
{
    public function format(LoggerLoggingEvent $event): string
    {
        $throwable = $event->getThrowableInformation();
        return json_encode(array_filter([
            'date' => date(DATE_ISO8601, $event->getTimestamp()),
            'level' => $event->getLevel()->toString(),
            'name' => $event->getLoggerName(),
            'file' => $event->getLocationInformation()->getFileName(),
            'line' => $event->getLocationInformation()->getLineNumber(),
            'message' => $event->getMessage(),
            'trace' => $throwable ? $throwable->getStringRepresentation() : null
        ]));
    }
}