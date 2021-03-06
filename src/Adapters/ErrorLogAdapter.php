<?php

namespace Nijens\Logging\Adapters;

/**
 * ErrorLogAdapter
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Logging\Adapters
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
