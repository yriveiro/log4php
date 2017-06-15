<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutJson extends LoggerLayout
{
    public function format(LoggerLoggingEvent $event): string
    {
        $context = $event->getLogger()->resolveExtendedContext();
        $throwable = $event->getThrowableInformation();
        $timestamp = date("Y-m-d\TH:i:sO", $event->getTimestamp());

        $event = [
            'date' => date(DATE_ISO8601, $event->getTimestamp()),
            'level' => $event->getLevel()->toString(),
            'name' => $event->getLoggerName(),
            'file' => $event->getLocationInformation()->getFileName(),
            'line' => $event->getLocationInformation()->getLineNumber(),
            'message' => $event->getRenderedMessage(),
            'trace' => $throwable ? $throwable->getStringRepresentation() : null
        ];

        return $timestamp . ' ' . json_encode(array_filter($event + $context));
    }
}