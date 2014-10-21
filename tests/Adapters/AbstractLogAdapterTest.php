<?php

namespace Nijens\Logging\Tests;

use Exception;
use Psr\Log\LogLevel;

/**
 * AbstractLogAdapterTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Logging\Tests
 **/
abstract class AbstractLogAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testImplementsLogAdapterInterface
     *
     * Tests if the log adapter implements the LogAdapterInterfaces
     *
     * @access public
     * @return void
     **/
    public function testImplementsLogAdapterInterface()
    {
        $this->assertInstanceOf("Nijens\\Logging\\Adapters\\LogAdapterInterface", $this->getAdapter() );
    }

    /**
     * testGetChannels
     *
     * Tests if
     *
     * @access public
     * @return void
     **/
    public function testGetChannels()
    {
        $adapter = $this->getAdapter();

        $this->assertInternalType("array", $adapter->getChannels() );
        $this->assertEmpty($adapter->getChannels() );

        $adapter->setChannels(array("foo", "bar") );

        $this->assertInternalType("array", $adapter->getChannels() );
        $this->assertSame(array("foo", "bar"), $adapter->getChannels() );
    }

    /**
     * testSetAndGetConfigurationValue
     *
     * Tests if
     *
     * @access public
     * @return void
     **/
    public function testSetAndGetConfigurationValue()
    {
        $adapter = $this->getAdapter();

        $this->assertInternalType("array", $adapter->getConfiguration() );
        $this->assertNull($adapter->getConfigurationValue("foo") );

        $adapter->setConfigurationValue("foo", "bar");

        $this->assertSame("bar", $adapter->getConfigurationValue("foo") );
    }

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
        $adapter = $this->getAdapter();
        $adapter->setChannels($channels);
        $adapter->setConfiguration($configuration);

        $this->assertSame($expectedReturnValue, $adapter->log($level, "Test message", $context) );
    }

    /**
     * provideTestLogAndIsLoggingForChannelAndLogLevel
     *
     * Tests if
     *
     * @access public
     * @return array
     **/
    public function provideTestLogAndIsLoggingForChannelAndLogLevel()
    {
        return array(
            array(LogLevel::NOTICE, array(), array(), array(), true),
            array(LogLevel::NOTICE, array(), array("foo"), array(), true),
            array(LogLevel::NOTICE, array("channel" => "foo"), array("foo"), array(), true),
            array(LogLevel::NOTICE, array("channel" => "foo"), array(), array(), true),
            array(LogLevel::NOTICE, array("channel" => "foo"), array("bar"), array(), false),
            array(LogLevel::NOTICE, array("channel" => "foo"), array("bar", "foo"), array(), true),
            array(LogLevel::INFO, array(), array(), array("level" => LogLevel::INFO), true),
            array(LogLevel::INFO, array(), array(), array("level" => LogLevel::NOTICE), false),
            array(LogLevel::INFO, array("channel" => "foo"), array("foo"), array("level" => LogLevel::INFO), true),
            array(LogLevel::INFO, array("channel" => "foo"), array("foo"), array("level" => LogLevel::NOTICE), false),
            array(LogLevel::INFO, array(), array("foo"), array("level" => LogLevel::NOTICE), false),
            array(LogLevel::ERROR, array("exception" => new Exception("Test exception") ), array("foo"), array("level" => LogLevel::NOTICE), true),
        );
    }

    /**
     * testLogThrowsInvalidArgumentExceptionOnInvalidLevel
     *
     * Tests if an InvalidArgumentException is thrown when an invalid LogLevel is used
     *
     * @expectedException Psr\Log\InvalidArgumentException
     * @access public
     * @return void
     **/
    public function testLogThrowsInvalidArgumentExceptionOnInvalidLogLevel()
    {
        $adapter = $this->getAdapter();
        $adapter->log("invalid level", "Foo");
    }

    /**
     * getAdapter
     *
     * Returns the log adapter being tested
     *
     * @access protected
     * @return LogAdapterInterfaces
     **/
    abstract protected function getAdapter();
}
