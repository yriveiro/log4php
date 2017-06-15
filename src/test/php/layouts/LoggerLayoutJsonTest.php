<?php

use Log4Php\Logger;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LoggerLayoutJsonTest extends TestCase
{
    public function testJsonFormat()
    {
        $config = LoggerTestHelper::getEchoJsonConfig();
        Logger::configure($config);

        $e = new Exception('exception');

        ob_start();
        $log = Logger::getLogger('LoggerTest');
        $log->error("my message", ['exception' => $e]); $line = __LINE__;
        $actual = ob_get_contents();
        ob_end_clean();

        $entry = json_decode($actual, true);
        Assert::assertArrayHasKey('level', $entry);
    }
}
