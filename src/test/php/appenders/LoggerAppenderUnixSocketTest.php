<?php

use Log4Php\Appenders\LoggerAppenderSocket;
use Log4Php\Appenders\LoggerAppenderUnixSocket;
use Log4Php\Layouts\LoggerLayoutSimple;
use Log4Php\Logger;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LoggerAppenderUnixSocketTest extends TestCase
{
    public function testLogging()
    {
        Logger::configure(array(
            'appenders' => array(
                'default' => array(
                    'class' => 'Log4Php\Appenders\LoggerAppenderUnixSocket',
                    'params' => array(
                        'path' => '/var/tmp/socket'
                    ),
                    'layout' => array(
                        'class' => 'Log4Php\Layouts\LoggerLayoutSimple'
                    )
                ),
            ),
            'rootLogger' => array(
                'appenders' => array('default'),
            ),
        ));

        $logger = Logger::getLogger("myLogger");
        $logger->trace("This message is a test");
        $logger->debug("This message is a test");
        $logger->info("This message is a test");
        $logger->warning("This message is a test");
        $logger->error("This message is a test");
        $logger->critical("This message is a test");

        Assert::assertTrue(true);
    }
}
