<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Log4Php\Layouts;

use Log4Php\Helpers\LoggerPatternParser;
use Log4Php\LoggerException;
use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;
use Log4Php\Pattern\LoggerPatternConverter;
use Log4Php\Pattern\LoggerPatternConverterClass;
use Log4Php\Pattern\LoggerPatternConverterCookie;
use Log4Php\Pattern\LoggerPatternConverterDate;
use Log4Php\Pattern\LoggerPatternConverterEnvironment;
use Log4Php\Pattern\LoggerPatternConverterFile;
use Log4Php\Pattern\LoggerPatternConverterLevel;
use Log4Php\Pattern\LoggerPatternConverterLine;
use Log4Php\Pattern\LoggerPatternConverterLocation;
use Log4Php\Pattern\LoggerPatternConverterLogger;
use Log4Php\Pattern\LoggerPatternConverterMDC;
use Log4Php\Pattern\LoggerPatternConverterMessage;
use Log4Php\Pattern\LoggerPatternConverterMethod;
use Log4Php\Pattern\LoggerPatternConverterNDC;
use Log4Php\Pattern\LoggerPatternConverterNewLine;
use Log4Php\Pattern\LoggerPatternConverterProcess;
use Log4Php\Pattern\LoggerPatternConverterRelative;
use Log4Php\Pattern\LoggerPatternConverterRequest;
use Log4Php\Pattern\LoggerPatternConverterServer;
use Log4Php\Pattern\LoggerPatternConverterSession;
use Log4Php\Pattern\LoggerPatternConverterSessionID;
use Log4Php\Pattern\LoggerPatternConverterThrowable;

/**
 * A flexible layout configurable with a pattern string.
 *
 * Configurable parameters:
 *
 * * conversionPattern - A string which controls the formatting of logging
 *   events. See docs for full specification.
 */
class LoggerLayoutPattern extends LoggerLayout
{

    /** Default conversion pattern */
    const DEFAULT_CONVERSION_PATTERN = '%date %-5level %logger %message%newline';

    /** Default conversion TTCC Pattern */
    const TTCC_CONVERSION_PATTERN = '%d [%t] %p %c %x - %m%n';

    /** The conversion pattern. */
    protected $pattern = self::DEFAULT_CONVERSION_PATTERN;

    /** Maps conversion keywords to the relevant converter (default implementation). */
    protected static $defaultConverterMap = array(
        'c'         => 'Log4Php\Pattern\LoggerPatternConverterLogger',
        'lo'        => 'Log4Php\Pattern\LoggerPatternConverterLogger',
        'logger'    => 'Log4Php\Pattern\LoggerPatternConverterLogger',
        'C'         => 'Log4Php\Pattern\LoggerPatternConverterClass',
        'class'     => 'Log4Php\Pattern\LoggerPatternConverterClass',
        'cookie'    => 'Log4Php\Pattern\LoggerPatternConverterCookie',
        'd'         => 'Log4Php\Pattern\LoggerPatternConverterDate',
        'date'      => 'Log4Php\Pattern\LoggerPatternConverterDate',
        'e'         => 'Log4Php\Pattern\LoggerPatternConverterEnvironment',
        'env'       => 'Log4Php\Pattern\LoggerPatternConverterEnvironment',
        'ex'        => 'Log4Php\Pattern\LoggerPatternConverterThrowable',
        'exception' => 'Log4Php\Pattern\LoggerPatternConverterThrowable',
        'throwable' => 'Log4Php\Pattern\LoggerPatternConverterThrowable',
        'F'         => 'Log4Php\Pattern\LoggerPatternConverterFile',
        'file'      => 'Log4Php\Pattern\LoggerPatternConverterFile',
        'l'         => 'Log4Php\Pattern\LoggerPatternConverterLocation',
        'location'  => 'Log4Php\Pattern\LoggerPatternConverterLocation',
        'L'         => 'Log4Php\Pattern\LoggerPatternConverterLine',
        'line'      => 'Log4Php\Pattern\LoggerPatternConverterLine',
        'm'         => 'Log4Php\Pattern\LoggerPatternConverterMessage',
        'msg'       => 'Log4Php\Pattern\LoggerPatternConverterMessage',
        'message'   => 'Log4Php\Pattern\LoggerPatternConverterMessage',
        'M'         => 'Log4Php\Pattern\LoggerPatternConverterMethod',
        'method'    => 'Log4Php\Pattern\LoggerPatternConverterMethod',
        'n'         => 'Log4Php\Pattern\LoggerPatternConverterNewLine',
        'newline'   => 'Log4Php\Pattern\LoggerPatternConverterNewLine',
        'p'         => 'Log4Php\Pattern\LoggerPatternConverterLevel',
        'le'        => 'Log4Php\Pattern\LoggerPatternConverterLevel',
        'level'     => 'Log4Php\Pattern\LoggerPatternConverterLevel',
        'r'         => 'Log4Php\Pattern\LoggerPatternConverterRelative',
        'relative'  => 'Log4Php\Pattern\LoggerPatternConverterRelative',
        'req'       => 'Log4Php\Pattern\LoggerPatternConverterRequest',
        'request'   => 'Log4Php\Pattern\LoggerPatternConverterRequest',
        's'         => 'Log4Php\Pattern\LoggerPatternConverterServer',
        'server'    => 'Log4Php\Pattern\LoggerPatternConverterServer',
        'ses'       => 'Log4Php\Pattern\LoggerPatternConverterSession',
        'session'   => 'Log4Php\Pattern\LoggerPatternConverterSession',
        'sid'       => 'Log4Php\Pattern\LoggerPatternConverterSessionID',
        'sessionid' => 'Log4Php\Pattern\LoggerPatternConverterSessionID',
        't'         => 'Log4Php\Pattern\LoggerPatternConverterProcess',
        'pid'       => 'Log4Php\Pattern\LoggerPatternConverterProcess',
        'process'   => 'Log4Php\Pattern\LoggerPatternConverterProcess',
        'x'         => 'Log4Php\Pattern\LoggerPatternConverterNDC',
        'ndc'       => 'Log4Php\Pattern\LoggerPatternConverterNDC',
        'X'         => 'Log4Php\Pattern\LoggerPatternConverterMDC',
        'mdc'       => 'Log4Php\Pattern\LoggerPatternConverterMDC',
    );

    /** Maps conversion keywords to the relevant converter. */
    protected $converterMap = array();

    /**
     * Head of a chain of Converters.
     * @var LoggerPatternConverter
     */
    private $head;

    /** Returns the default converter map. */
    public static function getDefaultConverterMap()
    {
        return self::$defaultConverterMap;
    }

    /** Constructor. Initializes the converter map. */
    public function __construct()
    {
        $this->converterMap = self::$defaultConverterMap;
    }

    /**
     * Sets the conversionPattern option. This is the string which
     * controls formatting and consists of a mix of literal content and
     * conversion specifiers.
     * @param string $conversionPattern
     */
    public function setConversionPattern($conversionPattern)
    {
        $this->pattern = $conversionPattern;
    }

    /**
     * Processes the conversion pattern and creates a corresponding chain of
     * pattern converters which will be used to format logging events.
     */
    public function activateOptions()
    {
        if (!isset($this->pattern)) {
            throw new LoggerException("Mandatory parameter 'conversionPattern' is not set.");
        }

        $parser = new LoggerPatternParser($this->pattern, $this->converterMap);
        $this->head = $parser->parse();
    }

    /**
     * Produces a formatted string as specified by the conversion pattern.
     *
     * @param LoggerLoggingEvent $event
     * @return string
     */
    public function format(LoggerLoggingEvent $event)
    {
        $sbuf = '';
        $converter = $this->head;
        while ($converter !== null) {
            $converter->format($sbuf, $event);
            $converter = $converter->next;
        }
        return $sbuf;
    }
}
