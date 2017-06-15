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
        $log = Logger::getLogger('LoggerTest');
        $log->addContextResolver(function() {
            return ['userId' => 22];
        });

        ob_start();
        $log->error("my message for {name}", ['exception' => $e, 'name' => 'you']); $line = __LINE__;
        $actual = ob_get_contents();
        ob_end_clean();

        $entry = json_decode($actual, true);
        Assert::assertArrayHasKey('level', $entry);
        Assert::assertEquals(22, $entry['userId']);
        Assert::assertEquals('my message for you', $entry['message']);
    }
}
