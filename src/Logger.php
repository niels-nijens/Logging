<?php

namespace AtomicPHP\Logging;

use \LogicException;
use \AtomicPHP\Logging\Adapters\ErrorLogAdapter;
use \AtomicPHP\Logging\Adapters\LogAdapterInterface;
use \Psr\Log\AbstractLogger;
use \Psr\Log\InvalidArgumentException;
use \Psr\Log\LogLevel;

/**
 * Logger
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging
 **/
class Logger extends AbstractLogger
{
    /**
     * The array with LogLevel constants
     *
     * @access protected
     * @var    array
     **/
    protected static $logLevels = array(
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    );

    /**
     * The array with LogAdapterInterface instances
     *
     * @access protected
     * @var    array
     **/
    protected $adapters = array();

    /**
     * __construct
     *
     * @access public
     * @param  array  $adapterConfigurations
     * @return Logger
     **/
    public function __construct(array $adapterConfigurations = array() )
    {
        $this->initialiseAdapters($adapterConfigurations);
    }

    /**
     * getAdapters
     *
     * Returns the array with registered log adapter instances
     *
     * @access public
     * @param  LogAdapterInterface  $adapter
     * @return void
     **/
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * getAdapter
     *
     * Returns the registered log adapter instance with $identifier
     *
     * @access public
     * @param  string   $identifier
     * @return LogAdapterInterface|null
     **/
    public function getAdapter($identifier)
    {
        if ($this->hasAdapter($identifier) ) {
            return $this->adapters[$identifier];
        }
    }

    /**
     * hasAdapter
     *
     * Returns true if a log adapter with $identifier has been registered
     *
     * @access public
     * @param  string   $identifier
     * @return boolean
     **/
    public function hasAdapter($identifier)
    {
        return array_key_exists($identifier, $this->adapters);
    }

    /**
     * addAdapter
     *
     * Adds a log adapter instance to the Logger
     *
     * @access public
     * @param  LogAdapterInterface  $adapter
     * @param  string               $identifier
     * @return void
     **/
    public function addAdapter(LogAdapterInterface $adapter, $identifier = null)
    {
        if (!empty($identifier) ) {
            if ($this->hasAdapter($identifier) ) {
                throw new LogicException("Another log adapter is registered with identifier '" . $identifier . "'.");
            }

            $this->adapters[$identifier] = $adapter;
        }
        else {
            array_push($this->adapters, $adapter);
        }
    }

    /**
     * log
     *
     * Logs with an arbitrary level
     *
     * @access public
     * @param  mixed    $level
     * @param  string   $message
     * @param  array    $context
     * @return void
     * @throws InvalidArgumentException
     **/
    public function log($level, $message, array $context = array() )
    {
        if (!$this->isValidLogLevel($level) ) {
            throw new InvalidArgumentException("'" . $level . "' is not a valid LogLevel.");
        }

        $this->addAdditionalContextData($context);
        $this->replaceMessagePlaceholdersWithContextData($message, $context);

        if (count($this->getAdapters() ) === 0) {
            $this->addAdapter(new ErrorLogAdapter() );
        }
        foreach ($this->adapters as $adapter) {
            $adapter->log($level, $message, $context);
        }
    }

    /**
     * isValidLogLevel
     *
     * Returns true if $level is a valid LogLevel constant
     *
     * @access public
     * @param  string   $level
     * @return boolean
     **/
    public static function isValidLogLevel($level)
    {
        return in_array($level, static::getLogLevels() );
    }

    /**
     * getLogLevels
     *
     * Returns the array with LogLevel constants
     *
     * @access public
     * @param  string|null  $level
     * @return array
     **/
    public static function getLogLevels($level = null)
    {
        if (!empty($level) && static::isValidLogLevel($level) ) {
            return array_slice(static::$logLevels, 0, array_search($level, static::$logLevels) + 1);
        }

        return static::$logLevels;
    }

    /**
     * initialiseAdapters
     *
     * Initialises log adapters based on the $adapterConfigurations
     *
     * @access protected
     * @param  array $adapterConfigurations
     * @return void
     **/
    protected function initialiseAdapters(array $adapterConfigurations) {
        foreach ($adapterConfigurations as $adapterConfiguration) {
            if (array_key_exists("className", $adapterConfiguration) && class_exists($adapterConfiguration["className"]) ) {
                $channels = array();
                $configuration = array();
                $identifier = null;
                if (array_key_exists("identifier", $adapterConfiguration) && is_string($adapterConfiguration["identifier"]) ) {
                    $identifier = $adapterConfiguration["identifier"];
                }
                if (array_key_exists("channels", $adapterConfiguration) && is_array($adapterConfiguration["channels"]) ) {
                    $channels = $adapterConfiguration["channels"];
                }
                if (array_key_exists("configuration", $adapterConfiguration) && is_array($adapterConfiguration["configuration"]) ) {
                    $configuration = $adapterConfiguration["configuration"];
                }

                $adapter = new $adapterConfiguration["className"]($channels, $configuration);
                $this->addAdapter($adapter, $identifier);
            }
        }
    }

    /**
     * replaceMessagePlaceholdersWithContextData
     *
     * Replaces placeholders in $message with data from $context
     *
     * @access protected
     * @param  string   $message
     * @param  array    $context
     * @return void
     **/
    protected function replaceMessagePlaceholdersWithContextData(& $message, array $context)
    {
        $replace = array();
        foreach ($context as $key => $value) {
            if (is_scalar($value) || (is_object($value) && method_exists($value, "__toString") ) ) {
                if (is_bool($value) || is_null($value) || is_integer($value) || is_float($value) ) {
                    $value = var_export($value, true);
                }
                else {
                    $value = strval($value);
                }

                $replace["{" . $key . "}"] = $value;
            }
        }

        $message = strtr($message, $replace);
    }

    /**
     * addAdditionalContextData
     *
     * Adds additional context data to the $context array
     *
     * @access public
     * @param  array    $context
     * @return boolean
     **/
    protected function addAdditionalContextData(array & $context)
    {
        $context = array_replace_recursive(array(), $context);
    }
}
