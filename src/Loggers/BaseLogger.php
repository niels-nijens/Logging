<?php

namespace AtomicPHP\Logging\Loggers;

use \Psr\Log\AbstractLogger;
use \Psr\Log\LogLevel;

/**
 * BaseLogger
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 **/
abstract class BaseLogger extends AbstractLogger
{
    /**
     * The array with LogLevel constants
     *
     * @access protected
     * @var array
     **/
    protected $logLevels = array(
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
     * isValidLogLevel
     *
     * Returns true if $level is a valid LogLevel constant
     *
     * @access protected
     * @param  string $level
     * @return boolean
     **/
    protected function isValidLogLevel($level) {
        return in_array($level, $this->logLevels);
    }

    /**
     * replacePlaceholdersWithContextData
     *
     * Replaces placeholders in $message with data from $context
     *
     * @access protected
     * @param  string $message
     * @param  array  $context
     * @return void
     **/
    protected function replacePlaceholdersWithContextData(& $message, array $context) {
        $replace = array();
        foreach ($context as $key => $value) {
            $replace["{" . $key . "}"] = $value;
        }

        $message = strtr($message, $replace);
    }
}
