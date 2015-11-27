<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramMedia
 *
 * @package jocks\libraries\instagram
 */
class InstagramMedia {

    /**
     * Internal instagram id.
     *
     * @var int
     */
    private $media_id;

    /**
     * Count of comments in this media.
     *
     * @var int
     */
    private $commentsCount = 0;

    /**
     * Count of likes in this media.
     *
     * @var int
     */
    private $likeCount = 0;

    /**
     * Caption of this media.
     *
     * @var InstagramCaption
     */
    private $caption;

    /**
     * Permalink of this media.
     *
     * @var string
     */
    private $permalink;

    /**
     * Timestamp of creating this media.
     *
     * @var int
     */
    private $created = 0;

    /**
     * Url of a media thumbnail.
     *
     * @var InstagramMediaPreview
     */
    private $thumbnail;

    /**
     * Url of a low resolution image.
     *
     * @var InstagramMediaPreview
     */
    private $lowResolutionImage;

    /**
     * Url of a standard resolution image.
     *
     * @var InstagramMediaPreview
     */
    private $standardResolutionImage;

    /**
     * Array of users in this media.
     *
     * @var array<InstagramMediaTaggedUser>
     */
    private $usersInMedia = array();

    /**
     * Used filter in this media.
     *
     * @var string
     */
    private $filter;

    /**
     * Location where this media was shot.
     *
     * @var InstagramLocation
     */
    private $location;

    /**
     * Uses tags for this media
     *
     * @var array<string>
     */
    private $tags = array();

    /**
     * InstagramMedia constructor.
     *
     * @param int $media_id
     * @param int $commentsCount
     * @param int $likeCount
     * @param InstagramCaption $caption
     * @param string $permalink
     * @param int $created
     * @param InstagramMediaPreview $thumbnail
     * @param InstagramMediaPreview $lowResolutionImage
     * @param InstagramMediaPreview $standardResolutionImage
     * @param InstagramCollection $usersInMedia
     * @param string $filter
     * @param InstagramLocation $location
     * @param array $tags
     */
    public function __construct($media_id, $commentsCount, $likeCount, InstagramCaption $caption, $permalink, $created, InstagramMediaPreview $thumbnail, InstagramMediaPreview $lowResolutionImage, InstagramMediaPreview $standardResolutionImage, InstagramCollection $usersInMedia, $filter, InstagramLocation $location, array $tags) {
        $this->media_id = $media_id;
        $this->commentsCount = $commentsCount;
        $this->likeCount = $likeCount;
        $this->caption = $caption;
        $this->permalink = $permalink;
        $this->created = $created;
        $this->thumbnail = $thumbnail;
        $this->lowResolutionImage = $lowResolutionImage;
        $this->standardResolutionImage = $standardResolutionImage;
        $this->usersInMedia = $usersInMedia;
        $this->filter = $filter;
        $this->location = $location;
        $this->tags = $tags;
    }

    /**
     * Get internal media id.
     *
     * @return int
     */
    public function getId() {
        return $this->media_id;
    }

    /**
     * Set internal media id.
     *
     * @param int $media_id
     */
    public function setId($media_id) {
        $this->media_id = $media_id;
    }

    /**
     * Get count of comments.
     *
     * @return int
     */
    public function getCommentsCount() {
        return $this->commentsCount;
    }

    /**
     * Set count of comments.
     *
     * @param int $commentsCount
     */
    public function setCommentsCount($commentsCount) {
        $this->commentsCount = $commentsCount;
    }

    /**
     * Get count of likes.
     *
     * @return int
     */
    public function getLikeCount() {
        return $this->likeCount;
    }

    /**
     * Set count of likes.
     *
     * @param int $likeCount
     */
    public function setLikeCount($likeCount) {
        $this->likeCount = $likeCount;
    }

    /**
     * Get caption of this media.
     *
     * @return InstagramCaption
     */
    public function getCaption() {
        return $this->caption;
    }

    /**
     * Set caption of this media.
     *
     * @param InstagramCaption $caption
     */
    public function setCaption($caption) {
        $this->caption = $caption;
    }

    /**
     * Get permalink of this media.
     *
     * @return string
     */
    public function getPermalink() {
        return $this->permalink;
    }

    /**
     * Set permalink of this media.
     *
     * @param string $permalink
     */
    public function setPermalink($permalink) {
        $this->permalink = $permalink;
    }

    /**
     * Get timestamp of creating this media.
     *
     * @return int
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Get timestamp of creating this media.
     *
     * @param int $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * Get url of thumbnail sized image of media.
     *
     * @return InstagramMediaPreview
     */
    public function getThumbnail() {
        return $this->thumbnail;
    }

    /**
     * Set url of thumbnail sized image of media.
     *
     * @param InstagramMediaPreview $thumbnail
     */
    public function setThumbnail($thumbnail) {
        $this->thumbnail = $thumbnail;
    }

    /**
     * Get url of a low resolution image.
     *
     * @return InstagramMediaPreview
     */
    public function getLowResolutionImage() {
        return $this->lowResolutionImage;
    }

    /**
     * Set url of a low resolution image.
     *
     * @param InstagramMediaPreview $lowResolutionImage
     */
    public function setLowResolutionImage($lowResolutionImage) {
        $this->lowResolutionImage = $lowResolutionImage;
    }

    /**
     * Get url of a standard resolution image.
     *
     * @return InstagramMediaPreview
     */
    public function getStandardResolutionImage() {
        return $this->standardResolutionImage;
    }

    /**
     * Set url of a standard resolution image.
     *
     * @param InstagramMediaPreview $standardResolutionImage
     */
    public function setStandardResolutionImage($standardResolutionImage) {
        $this->standardResolutionImage = $standardResolutionImage;
    }

    /**
     * Get array of users in this media.
     *
     * @return array
     */
    public function getUsersInMedia() {
        return $this->usersInMedia;
    }

    /**
     * Set array of users in this media.
     *
     * @param array $usersInMedia
     */
    public function setUsersInMedia($usersInMedia) {
        $this->usersInMedia = $usersInMedia;
    }

    /**
     * Get used filter.
     *
     * @return string
     */
    public function getFilter() {
        return $this->filter;
    }

    /**
     * Set used filter.
     *
     * @param string $filter
     */
    public function setFilter($filter) {
        $this->filter = $filter;
    }

    /**
     * Get location of this media.
     *
     * @return InstagramLocation
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set location of this media.
     *
     * @param InstagramLocation $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     * Get used tags.
     *
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Set used tags.
     *
     * @param array $tags
     */
    public function setTags($tags) {
        $this->tags = $tags;
    }

}

/**
 * Class InstagramMediaTaggedUser
 *
 * @package jocks\libraries\instagram
 */
class InstagramMediaTaggedUser extends InstagramUser {

    /**
     * Horizontal position in media.
     *
     * @var int
     */
    private $positionX = 0;

    /**
     * Vertical position in media.
     *
     * @var int
     */
    private $positionY = 0;

    /**
     * InstagramMediaTaggedUser constructor.
     *
     * @param int $userId
     * @param string $username
     * @param string $fullname
     * @param string $picture
     * @param float $positionX
     * @param float $positionY
     */
    public function __construct($userId, $username, $fullname, $picture, $positionX, $positionY) {
        parent::__construct($userId, $username, $fullname, null, null, $picture);
        $this->positionX = $positionX;
        $this->positionY = $positionY;
    }

    /**
     * Get the horizontal position.
     *
     * @return int
     */
    public function getPositionX() {
        return $this->positionX;
    }

    /**
     * Set the horizontal position.
     *
     * @param int $positionX
     */
    public function setPositionX($positionX) {
        $this->positionX = $positionX;
    }

    /**
     * Get the vertical position.
     *
     * @return int
     */
    public function getPositionY() {
        return $this->positionY;
    }

    /**
     * Set the vertical position.
     *
     * @param int $positionY
     */
    public function setPositionY($positionY) {
        $this->positionY = $positionY;
    }

}

/**
 * Class InstagramMediaPreview
 *
 * @package jocks\libraries\instagram
 */
class InstagramMediaPreview {

    /**
     * Url of this image.
     *
     * @var string
     */
    private $url;

    /**
     * Width of this image.
     *
     * @var int
     */
    private $width;

    /**
     * Height of this image.
     *
     * @var int
     */
    private $height;

    /**
     * InstagramMediaImage constructor.
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public function __construct($url, $width, $height) {
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Get url of this image.
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set url of this image.
     *
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * Get width of image.
     *
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Set width of image.
     *
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * Get height of image.
     *
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set height of image.
     *
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }

}