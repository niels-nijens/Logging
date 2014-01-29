<?php

namespace Nijens\Logging\Tests;

use Nijens\Logging\Logger;
use Nijens\Logging\Adapters\MemoryLogAdapter;
use Psr\Log\LogLevel;
use Psr\Log\Test\LoggerInterfaceTest;

/**
 * LoggerTest
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class LoggerTest extends LoggerInterfaceTest
{
    /**
     * The Logger instance used for testing
     *
     * @access protected
     * @var Logger
     **/
    protected $logger;

    /**
     * testAddAdapter
     *
     * Tests if Logger::addAdapter does not cause any errors
     *
     * @access public
     * @return void
     **/
    public function testAddAdapter()
    {
        $logger = new Logger();
        $logger->addAdapter(new MemoryLogAdapter() );

        $this->assertCount(1, $logger->getAdapters() );
    }

    /**
     * testAddAdapterWithIdentifier
     *
     * Tests if Logger::addAdapter does not cause any errors
     *
     * @depends testAddAdapter
     * @access public
     * @return void
     **/
    public function testAddAdapterWithIdentifier()
    {
        $logger = new Logger();
        $logger->addAdapter(new MemoryLogAdapter(), "test");

        $this->assertInstanceOf("Nijens\\Logging\\Adapters\\MemoryLogAdapter", $logger->getAdapter("test") );
    }

    /**
     * testAddAdapterWithIdentifierThrowsLogicExceptionWhenAlreadyUsed
     *
     * Tests if Logger::addAdapter throws a LogicException when the same identifier is used twice
     *
     * @depends testAddAdapterWithIdentifier
     * @expectedException LogicException
     * @access public
     * @return void
     **/
    public function testAddAdapterWithIdentifierThrowsLogicExceptionWhenAlreadyUsed()
    {
        $logger = new Logger();
        $logger->addAdapter(new MemoryLogAdapter(), "test");
        $logger->addAdapter(new MemoryLogAdapter(), "test");
    }

    /**
     * testInitialiseAdapters
     *
     * Tests if Logger::initialiseAdapters initialises the adapters during instantiation of a Logger instance
     *
     * @depends testAddAdapterWithIdentifierThrowsLogicExceptionWhenAlreadyUsed
     * @access public
     * @return void
     **/
    public function testInitialiseAdapters()
    {
        $adapterConfigurations = array(
            array("className" => "Nijens\\Logging\\Adapters\\MemoryLogAdapter", "identifier" => "test1", "configuration" => array() ),
            array("className" => "Nijens\\Logging\\Adapters\\MailLogAdapter", "identifier" => "test2", "configuration" => array() ),
        );

        $logger = new Logger($adapterConfigurations);

        $this->assertInstanceOf("Nijens\\Logging\\Adapters\\MemoryLogAdapter", $logger->getAdapter("test1") );
        $this->assertInstanceOf("Nijens\\Logging\\Adapters\\MailLogAdapter", $logger->getAdapter("test2") );
    }

    /**
     * testGetAdapters
     *
     * Tests if Logger::getAdapters returns an empty array
     *
     * @access public
     * @return void
     **/
    public function testGetAdapters()
    {
        $logger = new Logger();

        $this->assertInternalType("array", $logger->getAdapters() );
        $this->assertEmpty($logger->getAdapters() );
    }

    /**
     * testGetAdaptersWithAdapter
     *
     * Tests if Logger::getAdapters returns an array with the added adapter
     *
     * @depends testAddAdapter
     * @depends testGetAdapters
     * @access public
     * @return void
     **/
    public function testGetAdaptersWithAdapter()
    {
        $logger = new Logger();
        $logger->addAdapter(new MemoryLogAdapter() );

        $this->assertInternalType("array", $logger->getAdapters() );
        $this->assertCount(1, $logger->getAdapters() );
    }

    /**
     * testHasAdapter
     *
     * Tests if Logger::hasAdapter returns true when a log adapter is added with identifier
     *
     * @depends testAddAdapter
     * @access public
     * @return void
     **/
    public function testHasAdapter()
    {
        $logger = new Logger();

        $this->assertFalse($logger->hasAdapter("test") );

        $logger->addAdapter(new MemoryLogAdapter(), "test");

        $this->assertTrue($logger->hasAdapter("test") );
    }

    /**
     * testGetAdapter
     *
     * Tests if Logger::getAdapter returns null when no log adapter with identifier is found
     *
     * @access public
     * @return void
     **/
    public function testGetAdapter()
    {
        $logger = new Logger();

        $this->assertNull($logger->getAdapter("test") );
    }

    /**
     * testGetAdapterWithAdapter
     *
     * Tests if Logger::getAdapter returns the adapter by identfier
     *
     * @depends testAddAdapter
     * @access public
     * @return void
     **/
    public function testGetAdapterWithAdapter()
    {
        $adapter = new MemoryLogAdapter();

        $logger = new Logger();
        $logger->addAdapter($adapter, "test");

        $this->assertSame($adapter, $logger->getAdapter("test") );
    }

    /**
     * testLogWithoutAdapter
     *
     * Tests if Logger::log adds a default log adapter to log
     *
     * @depends testAddAdapter
     * @access public
     * @return void
     **/
    public function testLogWithoutAdapter()
    {
        $logger = new Logger();

        $this->assertCount(0, $logger->getAdapters() );

        $logger->log(LogLevel::DEBUG, "");

        $this->assertCount(1, $logger->getAdapters() );
    }

    /**
     * testIsValidLogLevel
     *
     * Tests if Logger::isValidLogLevel returns $expectedReturnValue for $level
     *
     * @dataProvider    provideTestIsValidLogLevel
     * @access public
     * @param  string   $level
     * @param  boolean  $expectedReturnValue
     * @return void
     **/
    public function testIsValidLogLevel($level, $expectedReturnValue)
    {
        $this->assertSame($expectedReturnValue, Logger::isValidLogLevel($level) );
    }

    /**
     * testGetLogLevels
     *
     * Tests if Logger::getLogLevels returns the expected array with LogLevel constants
     *
     * @access public
     * @return void
     **/
    public function testGetLogLevels()
    {
        $expectedArray = array(
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        );

        $this->assertEquals($expectedArray, Logger::getLogLevels() );
    }

    /**
     * testGetLogLevelsWithMinimumLevel
     *
     * Tests if Logger::getLogLevels with a minimum LogLevel constant as argument returns the expected array with LogLevel constants
     *
     * @access public
     * @return void
     **/
    public function testGetLogLevelsWithMinimumLevel()
    {
        $expectedArray = array(
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
        );

        $this->assertEquals($expectedArray, Logger::getLogLevels(LogLevel::WARNING) );
    }

    /**
     * provideTestIsValidLogLevel
     *
     * Returns the array with (in)valid LogLevel constants and the expected return value of Logger::isValidLogLevel
     *
     * @access public
     * @return array
     **/
    public function provideTestIsValidLogLevel()
    {
        return array(
            array(LogLevel::EMERGENCY, true),
            array(LogLevel::ALERT, true),
            array(LogLevel::CRITICAL, true),
            array(LogLevel::ERROR, true),
            array(LogLevel::WARNING, true),
            array(LogLevel::NOTICE, true),
            array(LogLevel::INFO, true),
            array(LogLevel::DEBUG, true),
            array("invalid loglevel", false),
        );
    }

    /**
     * getLogger
     *
     * Returns an instance of Logger
     *
     * @access public
     * @return Logger
     **/
    public function getLogger()
    {
        if ( !($this->logger instanceof Logger) ) {
            $this->logger = new Logger();
            $this->logger->addAdapter(new MemoryLogAdapter(), "test");
        }

        return $this->logger;
    }

    /**
     * getLogs
     *
     * Returns an array with log records
     *
     * @access public
     * @return array
     **/
    public function getLogs()
    {
        $adapter = $this->logger->getAdapter("test");

        $logs = array();
        foreach ($adapter->getRecords() as $record) {
            array_push($logs, $record["level"] . " " . $record["message"]);
        }

        return $logs;
    }
}
