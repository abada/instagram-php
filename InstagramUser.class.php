<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramUser
 *
 * @package jocks\libraries
 */
class InstagramUser {

    /**
     * Instagram id of this user.
     *
     * @var int
     */
    private $userId;

    /**
     * The uniqe username of the instagram user.
     *
     * @var string
     */
    private $username;

    /**
     * Full name of instagram user.
     *
     * @var string
     */
    private $fullname;

    /**
     * Biography of this instagram user.
     *
     * @var string
     */
    private $bio;

    /**
     * Instagram users website.
     *
     * @var string
     */
    private $website;

    /**
     * The url of the profile picture of this instagram user.
     *
     * @var string
     */
    private $picture;

    /**
     * The count of users uploaded media.
     *
     * @var int
     */
    private $mediaCount = 0;

    /**
     * The count of users this user follows.
     *
     * @var int
     */
    private $followsCount = 0;

    /**
     * The count of followers.
     *
     * @var int
     */
    private $followerCount = 0;

    /**
     * InstagramUser constructor.
     *
     * @param null $userId
     * @param null $username
     * @param null $fullname
     * @param null $bio
     * @param null $website
     * @param null $picture
     * @param int $mediaCount
     * @param int $followsCount
     * @param int $followerCount
     */
    public function __construct($userId = null, $username = null, $fullname = null, $bio = null, $website = null, $picture = null, $mediaCount = 0, $followsCount = 0, $followerCount = 0) {
        $this->userId = $userId;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->bio = $bio;
        $this->website = $website;
        $this->picture = $picture;
        $this->mediaCount = $mediaCount;
        $this->followsCount = $followsCount;
        $this->followerCount = $followerCount;
    }


    /**
     * Get the id of the instagram user.
     *
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set id of the instagram user.
     *
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * Get username of the instagram user.
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set username of the instagram user.
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get the full name of a instagram user.
     *
     * @return string|null
     */
    public function getFullname() {
        return $this->fullname;
    }

    /**
     * Set instagram users full name.
     *
     * @param string $fullname
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    /**
     * Get biography of a instagram user.
     *
     * @return string|null
     */
    public function getBio() {
        return $this->bio;
    }

    /**
     * Set biography of a instagram user.
     *
     * @param string $bio
     */
    public function setBio($bio) {
        $this->bio = $bio;
    }

    /**
     * Get website of the instagram user.
     *
     * @return string|null
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * Set website of the instagram user.
     *
     * @param string $website
     */
    public function setWebsite($website) {
        $this->website = $website;
    }

    /**
     * Get the profile picture of a instagram user.
     *
     * @return string|null
     */
    public function getPicture() {
        return $this->picture;
    }

    /**
     * Set the profile picture of a instagram user.
     *
     * @param string $picture
     */
    public function setPicture($picture) {
        $this->picture = $picture;
    }

    /**
     * Get the count this users media.
     *
     * @return int
     */
    public function getMediaCount() {
        return $this->mediaCount;
    }

    /**
     * Set count of this users media.
     *
     * @param int $mediaCount
     */
    public function setMediaCount($mediaCount) {
        $this->mediaCount = $mediaCount;
    }

    /**
     * Get count of users this user follows.
     *
     * @return int
     */
    public function getFollowsCount() {
        return $this->followsCount;
    }

    /**
     * Set count of users this user follows.
     *
     * @param int $followsCount
     */
    public function setFollowsCount($followsCount) {
        $this->followsCount = $followsCount;
    }

    /**
     * Get count of this users followers.
     *
     * @return int
     */
    public function getFollowerCount() {
        return $this->followerCount;
    }

    /**
     * Set count of this users followers.
     *
     * @param int $followerCount
     */
    public function setFollowerCount($followerCount) {
        $this->followerCount = $followerCount;
    }

}