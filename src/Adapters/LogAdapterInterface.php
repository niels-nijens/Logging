<?php
/**
 * LogAdapterInterface
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 **/
interface LogAdapterInterface {

    /**
     * __construct
     *
     * Creates a new log adapter for $channels with $configuration
     *
     * Basic adapter configuration parameters:
     * - level_threshold: The minimum LogLevel being logged
     *
     * @access public
     * @param  array|null $channels
     * @param  array      $configuration
     * @return LogAdapterInterface
     **/
    public function __construct(array $channels = null, array $configuration = array() );

    /**
     * getChannels
     *
     * Returns the channels this log adapter is logging
     *
     * @access public
     * @return array
     **/
    public function getChannels();

    /**
     * getConfiguration
     *
     * Returns the configuration of this log adapter
     *
     * @access public
     * @return array
     **/
    public function getConfiguration();

    /**
     * getConfigurationValue
     *
     * Returns the configuration value of $key
     *
     * @access public
     * @param  string $key
     * @return mixed
     **/
    public function getConfigurationValue($key);

    /**
     * setChannels
     *
     * Sets the channels this log adapter is logging for
     *
     * @access public
     * @param  array $channels
     * @return array
     **/
    public function setChannels(array $channels);

    /**
     * setConfiguration
     *
     * Sets the configuration in this log adapter
     *
     * @access public
     * @param  array $configuration
     * @return void
     * @throws InvalidArgumentException
     **/
    public function setConfiguration(array $configuration);

    /**
     * setConfigurationValue
     *
     * Sets a configuration $value for $key in this log adapter
     *
     * @access public
     * @param  array $configuration
     * @return void
     * @throws InvalidArgumentException
     **/
    public function setConfigurationValue($key, $value);

    /**
     * log
     *
     * Logs a $message of $level to this log adapter (with a certain $context).
     * Returns true when logging is successful or when success cannot be determined.
     *
     * @access public
     * @param  string $level
     * @param  string $message
     * @param  array  $context
     * @return boolean
     * @throws InvalidArgumentException
     **/
    public function log($level, $message, array $context = array() );
}
