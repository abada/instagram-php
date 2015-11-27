<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramCaption
 *
 * @package jocks\libraries\instagram
 */
class InstagramCaption {

    /**
     * The id of this caption.
     *
     * @var int
     */
    private $captionId;

    /**
     * The timestamp of creating this caption.
     *
     * @var int
     */
    private $created = 0;

    /**
     * The caption text.
     *
     * @var string
     */
    private $text;

    /**
     * The user who wrote this caption.
     *
     * @var InstagramCaptionFrom
     */
    private $from;

    /**
     * Get the id of this caption.
     *
     * @return int
     */
    public function getCaptionId() {
        return $this->captionId;
    }

    /**
     * Set id of this caption.
     *
     * @param int $captionId
     */
    public function setCaptionId($captionId) {
        $this->captionId = $captionId;
    }

    /**
     * Get timestamp of creating this caption.
     *
     * @return int
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set timestamp of creating this caption.
     *
     * @param int $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * Get caption content.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set caption content.
     *
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * Get caption from.
     *
     * @return InstagramCaptionFrom
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * Set caption from.
     *
     * @param InstagramCaptionFrom $from
     */
    public function setFrom($from) {
        $this->from = $from;
    }

}

/**
 * Class InstagramCaptionFrom
 *
 * @package jocks\libraries\instagram
 */
class InstagramCaptionFrom {

    /**
     * The id of this caption from.
     *
     * @var int
     */
    private $id;

    /**
     * The username of this caption from.
     *
     * @var string
     */
    private $username;

    /**
     * The full name of this caption from.
     *
     * @var string
     */
    private $fullname;

    /**
     * The type of this caption from.
     *
     * @var string
     */
    private $type;

    /**
     * InstagramCaptionFrom constructor.
     *
     * @param int $id
     * @param string $username
     * @param string $fullname
     * @param string $type
     */
    public function __construct($id, $username, $fullname, $type) {
        $this->id = $id;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->type = $type;
    }

    /**
     * Get the if of this caption from.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the id of this caption from.
     *
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get the username of this caption from.
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set the username of this caption from.
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get the full name of this caption from.
     *
     * @return string
     */
    public function getFullname() {
        return $this->fullname;
    }

    /**
     * Set the full name of this caption from.
     *
     * @param string $fullname
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    /**
     * Get type of this caption from.
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set type of this caption from.
     *
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

}