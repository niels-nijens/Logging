<?php

namespace AtomicPHP\Logging\Tests;

use \AtomicPHP\Logging\Adapters\SentryLogAdapter;

/**
 * SentryLogAdapterTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Tests
 * */
class SentryLogAdapterTest extends AbstractLogAdapterTest
{
    /**
     * The log adapter instance used for testing
     *
     * @access protected
     * @var    SentryLogAdapter
     **/
    protected $adapter;

    /**
     * testLogAndIsLoggingForChannelAndLogLevel
     *
     * Tests if
     *
     * @dataProvider provideTestLogAndIsLoggingForChannelAndLogLevel
     * @access public
     * @param  string   $level
     * @param  array    $context
     * @param  array    $configuration
     * @param  boolean  $expectedReturnValue
     * @return void
     **/
    public function testLogAndIsLoggingForChannelAndLogLevel($level, array $context, array $channels, array $configuration, $expectedReturnValue)
    {
        parent::testLogAndIsLoggingForChannelAndLogLevel($level, $context, $channels, $configuration, $expectedReturnValue);

        sleep(60);
    }

    /**
     * getAdapter
     *
     * Returns the log adapter instance for testing
     *
     * @access protected
     * @return SentryLogAdapter
     **/
    protected function getAdapter()
    {
        $this->adapter = new SentryLogAdapter(array(), array("dsn" => "https://013b55d20bd649c9b5415e52cedf53bc:540243d7c1ce4fb9b2e306081b74d500@app.getsentry.com/13505") );

        return $this->adapter;
    }

    /**
     * getLogs
     *
     * Returns the log results
     *
     * @access protected
     * @return string
     **/
    protected function getLogs()
    {
        return "";
    }
}
