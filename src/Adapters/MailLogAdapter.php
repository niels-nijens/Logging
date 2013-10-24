<?php

namespace AtomicPHP\Logging\Adapters;

/**
 * MailLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Adapters
 **/
class MailLogAdapter extends AbstractLogAdapter
{
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
    protected function write($level, $message, array $context = array() ) {
        $subject = strtoupper($level) . ": " . $message;
        $body = "";
        foreach ($context as $contextKey => $contextValue) {
            $body .= ucfirst($contextKey) . ":\n" . var_export($contextValue, true);
        }

        return mail($this->getConfigurationValue("recipient"), $subject, $body);
    }
}
