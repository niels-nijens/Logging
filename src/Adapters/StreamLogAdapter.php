<?php

namespace AtomicPHP\Logging\Adapters;

use \RuntimeException;

/**
 * StreamLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Adapters
 **/
class StreamLogAdapter extends AbstractLogAdapter
{
    /**
     * The resource this log adapter is logging to
     *
     * @access protected
     * @var    resource
     */
    protected $resource;

    /**
     * __construct
     *
     * Creates a new log adapter instance
     *
     * @access public
     * @param  array    $channels
     * @param  array    $configuration
     * @return LogAdapterInterface
     **/
    public function __construct(array $channels = array(), array $configuration = array() )
    {
        parent::__construct($channels, $configuration);

        $resource = @fopen($this->configuration["resource"], "a");
        if (!is_resource($resource) ) {
            throw new RuntimeException("Cannot open resource '" . $this->configuration["resource"] . "' for " . __CLASS__ . ".");
        }

        $this->resource = $resource;
    }

    /**
     * write
     *
     * Writes the message to the log
     *
     * @access protected
     * @param  string   $level
     * @param  string   $message
     * @param  array    $context
     * @return boolean
     **/
    protected function write($level, $message, array $context = array() )
    {
        $timestamp = new DateTime();

        fwrite($this->resource, sprintf("%s %s %s", array($timestamp->format("Y-m-d H:i:s"), strtoupper($level), $message) ) );
    }
}