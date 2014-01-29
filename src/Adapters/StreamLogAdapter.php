<?php

namespace Nijens\Logging\Adapters;

use DateTime;
use RuntimeException;

/**
 * StreamLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Logging\Adapters
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

        $resource = @fopen($this->getConfigurationValue("resource"), "a");
        if (!is_resource($resource) ) {
            throw new RuntimeException("Cannot open resource '" . $this->getConfigurationValue("resource") . "' for " . __CLASS__ . ".");
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

        return (fwrite($this->resource, vsprintf("%s %s %s\n", array($timestamp->format("Y-m-d H:i:s"), strtoupper($level), $message) ) ) !== false);
    }
}
