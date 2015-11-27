<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramOAuthToken
 *
 * @package jocks\libraries
 */
class InstagramOAuthToken {

    /**
     * The access token for further operations in this session.
     *
     * @var string
     */
    private $accessToken;

    /**
     * The instagram user of this session.
     *
     * @var InstagramUser
     */
    private $user;

    /**
     * InstagramOAuthToken constructor.
     *
     * @param string $accessToken
     * @param InstagramUser $user
     */
    public function __construct($accessToken, InstagramUser $user) {
        $this->accessToken = $accessToken;
        $this->user = $user;
    }


    /**
     * Get the access token of this session.
     *
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Set the access token of this session.
     *
     * @param string $accessToken
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * Get the user of this session.
     *
     * @return InstagramUser
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set the user of this session.
     *
     * @param InstagramUser $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

}