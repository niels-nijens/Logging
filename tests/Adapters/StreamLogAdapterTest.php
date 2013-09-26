<?php

namespace AtomicPHP\Logging\Tests;

use \AtomicPHP\Logging\Adapters\StreamLogAdapter;

/**
 * MemoryLogAdapterTest
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 **/
class StreamLogAdapterTest extends AbstractLogAdapterTest
{
    /**
     * The log adapter instance used for testing
     *
     * @access protected
     * @var    StreamLogAdapter
     **/
    protected $adapter;

    /**
     * testNewInstanceWithoutResourceInConfigurationThrowsRuntimeException
     *
     * Tests if creation of a new StreamLogAdapter instance throws a RuntimeException
     *
     * @expectedException RuntimeException
     * @access public
     * @return void
     **/
    public function testNewInstanceWithoutResourceInConfigurationThrowsRuntimeException() {
        new StreamLogAdapter();
    }

    /**
     * getAdapter
     *
     * Returns the log adapter instance for testing
     *
     * @access protected
     * @return StreamLogAdapter
     **/
    protected function getAdapter()
    {
        $this->adapter = new StreamLogAdapter(array(), array("resource" => "test.log") );

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

