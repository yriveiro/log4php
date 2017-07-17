<?php

use Log4Php\Appenders\LoggerAppenderEcho;
use Log4Php\Layouts\LoggerLayoutSimple;
use Log4Php\Logger;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MessageInterpolationTest extends TestCase
{
    public function testMessageInterpolation()
    {
        Logger::configure(array(
            'rootLogger' => array(
                'appenders' => array('default'),
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'Log4Php\Appenders\LoggerAppenderEcho',
                    'layout' => array(
                        'class' => 'Log4Php\Layouts\LoggerLayoutSimple'
                    ),
                )
            )
        ));

        $logger = Logger::getLogger('MessageInterpolationTest');

        ob_start();
        $logger->info('Message interpolation {status}', array('status' => 'successful'));
        $actual = trim(ob_get_clean());

        Assert::assertEquals('INFO - Message interpolation successful', $actual);
    }
}
