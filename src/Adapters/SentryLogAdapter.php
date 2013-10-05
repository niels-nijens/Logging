<?php

namespace AtomicPHP\Logging\Adapters;

use \Exception;
use \Raven_Client;
use \Psr\Log\LogLevel;

/**
 * SentryLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Adapters
 * */
class SentryLogAdapter extends AbstractLogAdapter
{
    /**
     * The Raven_Client instance used to send the log messages to Sentry
     *
     * @access protected
     * @var    Raven_Client
     **/
    protected $client;

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
        parent::__construct($channels, $configuration);

        if ( !($this->client = $this->getConfigurationValue("raven_client") ) instanceof Raven_Client) {
            $this->client = new Raven_Client($this->getConfigurationValue("dsn") );

            $this->setConfigurationValue("raven_client", $this->client);
        }
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
        if (array_key_exists("exception", $context) && $context["exception"] instanceof Exception) {
            $data = array(
                "level" => $this->translateLogLevelToSentryLevel($level),
                "extra" => $context,
            );

            $this->client->captureException($context["exception"], $data);
        }
        else {
            $this->client->captureMessage($message, $context, $this->translateLogLevelToSentryLevel($level) );
        }

        if (is_null($this->client->getLastError() ) ) {
            return true;
        }

        return false;
    }

    /**
     * translateLogLevelToSentryLevel
     *
     * Returns a Raven_Client constant value for $level
     *
     * @access protected
     * @param  string   $level
     * @return string
     **/
    protected function translateLogLevelToSentryLevel($level)
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
                $level = Raven_Client::FATAL;
                break;
            case LogLevel::ERROR:
                $level = Raven_Client::ERROR;
                break;
            case LogLevel::WARNING:
                $level = Raven_Client::WARNING;
                break;
            case LogLevel::NOTICE:
            case LogLevel::INFO:
                $level = Raven_Client::INFO;
                break;
            case LogLevel::DEBUG:
                $level = Raven_Client::DEBUG;
        }

        return $level;
    }
}
