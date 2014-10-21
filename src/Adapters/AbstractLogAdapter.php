<?php

namespace Nijens\Logging\Adapters;

use Nijens\Logging\Logger;
use Psr\Log\InvalidArgumentException;

/**
 * AbstractLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Logging\Adapters
 **/
abstract class AbstractLogAdapter implements LogAdapterInterface
{
    /**
     * The array with channels this log adapter is logging
     *
     * @access protected
     * @var    array
     **/
    protected $channels = array();

    /**
     * The array with configuration parameters for the log adapter
     *
     * @access protected
     * @var    array
     **/
    protected $configuration = array();

    /**
     * __construct
     *
     * Creates a new log adapter instance
     *
     * @access public
     * @param  array $channels
     * @param  array $configuration
     * @return LogAdapterInterface
     **/
    public function __construct(array $channels = array(), array $configuration = array() )
    {
        $this->setChannels($channels);
        $this->setConfiguration($configuration);
    }

    /**
     * getChannels
     *
     * Returns the channels this log adapter is logging
     *
     * @access public
     * @return array
     **/
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * getConfiguration
     *
     * Returns the configuration of this log adapter
     *
     * @access public
     * @return array
     **/
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * getConfigurationValue
     *
     * Returns the configuration value of $key
     *
     * @access public
     * @param  string $key
     * @return mixed
     **/
    public function getConfigurationValue($key)
    {
        if (array_key_exists($key, $this->configuration) ) {
            return $this->configuration[$key];
        }

        return null;
    }

    /**
     * setChannels
     *
     * Sets the channels this log adapter is logging for
     *
     * @access public
     * @param  array $channels
     * @return array
     **/
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * setConfiguration
     *
     * Sets the configuration in this log adapter
     *
     * @access public
     * @param  array $configuration
     * @return void
     **/
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * setConfigurationValue
     *
     * Sets a configuration $value for $key in this log adapter
     *
     * @access public
     * @param  array $configuration
     * @return void
     **/
    public function setConfigurationValue($key, $value)
    {
        $this->configuration[$key] = $value;
    }

    /**
     * log
     *
     * Logs a $message of $level to this log adapter (with a certain $context).
     * Returns true when logging is successful or when success cannot be determined.
     *
     * @access public
     * @param  string   $level
     * @param  string   $message
     * @param  array    $context
     * @return boolean
     * @throws InvalidArgumentException
     **/
    public function log($level, $message, array $context = array() )
    {
        if (!Logger::isValidLogLevel($level) ) {
            throw new InvalidArgumentException("'" . $level . "' is not a valid LogLevel.");
        }

        if ($this->isLoggingForChannelAndLogLevel($level, $context) ) {
            return $this->write($level, $message, $context);
        }

        return false;
    }

    /**
     * isLoggingForChannelAndLogLevel
     *
     * Returns true when:
     * - $level is equal to or above the minimum configured log level
     *
     * And
     *
     * - No channel has been set in $context
     * - No channels have been set in the adapter (meaning log all channels)
     * - The channel in $context is set as channel in this adapter
     *
     * @access public
     * @param  string   $level
     * @param  array    $context
     * @return boolean
     **/
    protected function isLoggingForChannelAndLogLevel($level, array $context)
    {
        $isLevel = $isLogging = false;
        if (in_array($level, Logger::getLogLevels($this->getConfigurationValue("level") ) ) ) {
            $isLevel = true;
        }

        if ($isLevel === true && (!array_key_exists("channel", $context) || count($this->getChannels() ) === 0 || in_array($context["channel"], $this->getChannels() ) ) ) {
            $isLogging = true;
        }

        return $isLogging;
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
    abstract protected function write($level, $message, array $context = array() );
}
