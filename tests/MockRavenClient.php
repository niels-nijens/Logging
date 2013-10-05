<?php

namespace AtomicPHP\Logging\Tests;

use \Raven_Client;

/**
 * MockRavenClient
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package AtomicPHP\Logging\Tests
 * */
class MockRavenClient extends Raven_Client
{
    /**
     * The array with data being sent to Sentry
     *
     * @access public
     * @var    array
     **/
    public $lastSentData;

    /**
     * send
     *
     * Catches the data normally being sent to Sentry
     *
     * @access public
     * @param  array $data
     * @return void
     **/
    public function send($data)
    {
        $this->lastSentData = $data;
    }

    /**
     * setLastError
     *
     * Sets the last error for testing purposes
     *
     * @access public
     * @param  string $lastError
     * @return void
     **/
    public function setLastError($lastError)
    {
        $this->_lasterror = $lastError;
    }
}
