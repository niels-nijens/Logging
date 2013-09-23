<?php

namespace AtomicPHP\Logging\Adapters;

/**
 * ErrorLogAdapter
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 * */
class ErrorLogAdapter extends AbstractLogAdapter
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
    protected function write($level, $message, array $context = array() )
    {
        error_log($message);
    }
}
