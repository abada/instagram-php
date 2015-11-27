<?php

// Package
namespace jocks\libraries\instagram\exceptions;
use Exception;

/**
 * Class InstagramException
 *
 * @package jocks\libraries
 */
class InstagramException extends Exception {

    /**
     * Exception type
     *
     * @var string
     */
    private $type;

    /**
     * InstagramException extended constructor.
     *
     * @param null $message
     * @param int $code
     * @param null $type
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, $type = null, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->setType($type);
    }

    /**
     * Get exception type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set exception type
     *
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

}