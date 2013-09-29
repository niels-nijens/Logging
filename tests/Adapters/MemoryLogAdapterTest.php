<?php

namespace AtomicPHP\Logging\Tests;

use \AtomicPHP\Logging\Adapters\MemoryLogAdapter;

/**
 * MemoryLogAdapterTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Tests
 **/
class MemoryLogAdapterTest extends AbstractLogAdapterTest
{
    /**
     * The log adapter instance used for testing
     *
     * @access protected
     * @var    MemoryLogAdapter
     **/
    protected $adapter;

    /**
     * getAdapter
     *
     * Returns the log adapter instance for testing
     *
     * @access protected
     * @return MemoryLogAdapter
     **/
    protected function getAdapter()
    {
        $this->adapter = new MemoryLogAdapter();

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
    public function getLogs()
    {
        return $this->adapter->getRecords();
    }
}
