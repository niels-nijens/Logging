<?php

namespace AtomicPHP\Logging\Adapters;

use \DateTime;

/**
 * MemoryLogger
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Adapters
 **/
class MemoryLogAdapter extends AbstractLogAdapter
{
    /**
     * The array with logged records
     *
     * @access protected
     * @var    array
     **/
    protected $records = array();

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
        $record = array(
            "timestamp" => new DateTime(),
            "level" => $level,
            "message" => $message,
            "context" => $context,
        );

        array_push($this->records, $record);

        return true;
    }

    /**
     * getRecords
     *
     * Returns the array with logged records
     *
     * @access public
     * @return array
     **/
    public function getRecords()
    {
        return $this->records;
    }
}
