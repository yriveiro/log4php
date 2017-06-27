<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutJson extends LoggerLayout
{
    public function format(LoggerLoggingEvent $event): string
    {
        $context = $event->getContext() + $event->getLogger()->resolveExtendedContext();
        $throwable = $event->getThrowableInformation();

        $event = [
            'date' => date(DATE_ISO8601, $event->getTimestamp()),
            'level' => $event->getLevel()->toString(),
            'name' => $event->getLoggerName(),
            'file' => $event->getLocationInformation()->getFileName(),
            'line' => $event->getLocationInformation()->getLineNumber(),
            'message' => $event->getRenderedMessage(),
            'trace' => $throwable ? $throwable->getStringRepresentation() : null
        ];

        return json_encode(array_filter($event + $context));
    }
}