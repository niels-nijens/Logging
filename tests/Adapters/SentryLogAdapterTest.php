<?php

namespace Nijens\Logging\Tests;

use Nijens\Logging\Adapters\SentryLogAdapter;
use Psr\Log\LogLevel;

/**
 * SentryLogAdapterTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Logging\Tests
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
     * testInstantiationWithDSNSetsNewRavenClientInstanceAsConfigationValue
     *
     * Tests if instantiation of a new SentryLogAdapter adds the Raven_Client as configuration value with key "raven_client"
     *
     * @access public
     * @return void
     **/
    public function testInstantiationWithDSNSetsNewRavenClientInstanceAsConfigationValue() {
        $adapter = new SentryLogAdapter(array(), array("dsn" => "https://013b55d20bd649c9b5415e52cedf53bc:540243d7c1ce4fb9b2e306081b74d500@app.getsentry.com/13505") );

        $this->assertInstanceOf("Raven_Client", $adapter->getConfigurationValue("raven_client") );
    }

    /**
     * testLogReturnsFalseOnRavenClientError
     *
     * Tests if SentryLogAdapter::log returns false when an error occured in the Raven_Client
     *
     * @access public
     * @return void
     **/
    public function testLogReturnsFalseOnRavenClientError() {
        $client = new MockRavenClient("https://013b55d20bd649c9b5415e52cedf53bc:540243d7c1ce4fb9b2e306081b74d500@app.getsentry.com/13505");
        $client->setLastError("Error");

        $adapter = new SentryLogAdapter(array(), array("raven_client" => $client) );
        $this->assertFalse($adapter->log(LogLevel::INFO, "Test message") );
    }

    /**
     * testTranslateLogLevelToSentryLevel
     *
     * Tests if SentryLogAdapter::translateLogLevelToSentryLevel returns the expected Raven_Client constant for a LogLevel constant
     *
     * @dataProvider provideTestTranslateLogLevelToSentryLevel
     * @access public
     * @param  string $logLevel
     * @param  string $ravenClientLevel
     * @return void
     **/
    public function testTranslateLogLevelToSentryLevel($logLevel, $ravenClientLevel) {
        $adapter = $this->getAdapter();
        $adapter->log($logLevel, "Test message");

        $ravenClient = $adapter->getConfigurationValue("raven_client");
        $this->assertEquals($ravenClientLevel, $ravenClient->lastSentData["level"]);
    }

    /**
     * provideTestTranslateLogLevelToSentryLevel
     *
     * Returns an array with LogLevel and Raven_Client constants for testing
     *
     * @access public
     * @return array
     **/
    public function provideTestTranslateLogLevelToSentryLevel() {
        return array(
            array(LogLevel::EMERGENCY, MockRavenClient::FATAL),
            array(LogLevel::ALERT, MockRavenClient::FATAL),
            array(LogLevel::CRITICAL, MockRavenClient::FATAL),
            array(LogLevel::ERROR, MockRavenClient::ERROR),
            array(LogLevel::WARNING, MockRavenClient::WARNING),
            array(LogLevel::NOTICE, MockRavenClient::INFO),
            array(LogLevel::INFO, MockRavenClient::INFO),
            array(LogLevel::DEBUG, MockRavenClient::DEBUG),
        );
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
        $options = array(
            "raven_client" => new MockRavenClient("https://013b55d20bd649c9b5415e52cedf53bc:540243d7c1ce4fb9b2e306081b74d500@app.getsentry.com/13505"),
        );

        $this->adapter = new SentryLogAdapter(array(), $options);

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
