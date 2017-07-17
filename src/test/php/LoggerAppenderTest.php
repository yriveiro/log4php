<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   tests
 * @package    log4php
 * @subpackage appenders
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version    $Revision$
 * @link       http://logging.apache.org/log4php
 */

use Log4Php\Appenders\LoggerAppenderEcho;
use Log4Php\Appenders\LoggerAppenderNull;
use Log4Php\Filters\LoggerFilterDenyAll;
use Log4Php\Filters\LoggerFilterLevelMatch;
use Log4Php\Layouts\LoggerLayoutSimple;
use Log4Php\Logger;
use Log4Php\LoggerLevel;
use Log4Php\LoggerLoggingEvent;
use PHPUnit\Framework\TestCase;

class LoggerAppenderTest extends TestCase
{
    public function testThreshold()
    {
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");

        $layout = new LoggerLayoutSimple();
        $appender->setLayout($layout);

        $warn = LoggerLevel::getLevelWarning();
        $appender->setThreshold($warn);
        $appender->activateOptions();

        $event = new LoggerLoggingEvent('Log4Php\Appenders\LoggerAppenderEchoTest', new Logger("TEST"), LoggerLevel::getLevelCritical(), "testmessage");
        ob_start();
        $appender->doAppend($event);
        $v = ob_get_contents();
        ob_end_clean();
        $e = "CRITICAL - testmessage" . PHP_EOL;
        self::assertEquals($e, $v);

        $event = new LoggerLoggingEvent('Log4Php\Appenders\LoggerAppenderEchoTest', new Logger("TEST"), LoggerLevel::getLevelError(), "testmessage");
        ob_start();
        $appender->doAppend($event);
        $v = ob_get_contents();
        ob_end_clean();
        $e = "ERROR - testmessage" . PHP_EOL;
        self::assertEquals($e, $v);

        $event = new LoggerLoggingEvent('Log4Php\Appenders\LoggerAppenderEchoTest', new Logger("TEST"), LoggerLevel::getLevelWarning(), "testmessage");
        ob_start();
        $appender->doAppend($event);
        $v = ob_get_contents();
        ob_end_clean();
        $e = "WARNING - testmessage" . PHP_EOL;
        self::assertEquals($e, $v);

        $event = new LoggerLoggingEvent('Log4Php\Appenders\LoggerAppenderEchoTest', new Logger("TEST"), LoggerLevel::getLevelInfo(), "testmessage");
        ob_start();
        $appender->doAppend($event);
        $v = ob_get_contents();
        ob_end_clean();
        $e = "";
        self::assertEquals($e, $v);

        $event = new LoggerLoggingEvent('Log4Php\Appenders\LoggerAppenderEchoTest', new Logger("TEST"), LoggerLevel::getLevelDebug(), "testmessage");
        ob_start();
        $appender->doAppend($event);
        $v = ob_get_contents();
        ob_end_clean();
        $e = "";
        self::assertEquals($e, $v);
    }

    public function testGetThreshold()
    {
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");

        $layout = new LoggerLayoutSimple();
        $appender->setLayout($layout);

        $warn = LoggerLevel::getLevelWarning();
        $appender->setThreshold($warn);

        $a = $appender->getThreshold();
        self::assertEquals($warn, $a);
    }

    public function testSetStringThreshold()
    {
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");

        $layout = new LoggerLayoutSimple();
        $appender->setLayout($layout);

        $warn = LoggerLevel::getLevelWarning();
        $appender->setThreshold('WARN');
        $a = $appender->getThreshold();
        self::assertEquals($warn, $a);

        $e = LoggerLevel::getLevelCritical();
        $appender->setThreshold('CRITICAL');
        $a = $appender->getThreshold();
        self::assertEquals($e, $a);

        $e = LoggerLevel::getLevelError();
        $appender->setThreshold('ERROR');
        $a = $appender->getThreshold();
        self::assertEquals($e, $a);

        $e = LoggerLevel::getLevelDebug();
        $appender->setThreshold('DEBUG');
        $a = $appender->getThreshold();
        self::assertEquals($e, $a);

        $e = LoggerLevel::getLevelInfo();
        $appender->setThreshold('INFO');
        $a = $appender->getThreshold();
        self::assertEquals($e, $a);
    }

    public function testSetFilter()
    {
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");

        $layout = new LoggerLayoutSimple();
        $appender->setLayout($layout);

        $filter = new LoggerFilterDenyAll();
        $appender->addFilter($filter);

        $filter2 = new LoggerFilterLevelMatch();
        $appender->addFilter($filter2);

        $first = $appender->getFilter();
        self::assertEquals($first, $filter);

        $next = $first->getNext();
        self::assertEquals($next, $filter2);

        $appender->clearFilters();
        $nullFilter = $appender->getFilter();
        self::assertNull($nullFilter);
    }

    public function testInstanciateWithLayout()
    {
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");
        $actual = $appender->getLayout();
        $this->assertInstanceof('Log4Php\Layouts\LoggerLayoutSimple', $actual);
    }

    public function testOverwriteLayout()
    {
        $layout = new LoggerLayoutSimple();
        $appender = new LoggerAppenderEcho("LoggerAppenderTest");
        $appender->setLayout($layout);
        $actual = $appender->getLayout();
        $this->assertEquals($layout, $actual);
    }

    public function testRequiresNoLayout()
    {
        $appender = new LoggerAppenderNull("LoggerAppenderTest");
        $actual = $appender->getLayout();
        $this->assertNull($actual);
    }
}
