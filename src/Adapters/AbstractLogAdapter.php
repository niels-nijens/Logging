<?php

namespace AtomicPHP\Logging\Adapters;

/**
 * AbstractLogAdapter
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
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
    public function __construct(array $channels = array(), array $configuration = array() ) {
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
    public function getChannels() {
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
    public function getConfiguration() {
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
    public function getConfigurationValue($key) {
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
    public function setChannels(array $channels) {
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
    public function setConfiguration(array $configuration) {
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
    public function setConfigurationValue($key, $value) {
        $this->configuration[$key] = $value;
    }

    /**
     * isLoggingForChannel
     *
     * Returns true when:
     * - No channel has been set in $context
     * - No channels have been set in the adapter (meaning log all channels)
     * - The channel in $context is set as channel in this adapter
     *
     * @access public
     * @param  array $configuration
     * @return void
     **/
    protected function isLoggingForChannel(array $context) {
        if (!array_key_exists("channel", $context) || count($this->getChannels() ) === 0 || in_array($context["channel"], $this->getChannels() ) ) {
            return true;
        }

        return false;
    }
}
