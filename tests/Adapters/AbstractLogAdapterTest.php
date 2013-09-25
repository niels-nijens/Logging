<?php

namespace AtomicPHP\Logging\Tests;

/**
 * AbstractLogAdapterTest
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
abstract class AbstractLogAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testLogThrowsInvalidArgumentExceptionOnInvalidLevel
     *
     * Tests if the log adapter implements the LogAdapterInterfaces
     *
     * @access public
     * @return void
     **/
    public function testImplementsLogAdapterInterface()
    {
        $this->assertInstanceOf("AtomicPHP\\Logging\\Adapters\\LogAdapterInterface", $this->getAdapter() );
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
