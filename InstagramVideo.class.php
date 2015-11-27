<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramVideo
 *
 * @package jocks\libraries\instagram
 */
class InstagramVideo extends InstagramMedia {

    /**
     * A low resolution video
     *
     * @var InstagramMediaPreview
     */
    private $lowResolutionVideo;

    /**
     * A standard resolution video
     *
     * @var InstagramMediaPreview
     */
    private $standardResolutionVideo;

    /**
     * InstagramVideo constructor.
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
     * @param array $usersInMedia
     * @param $filter
     * @param InstagramLocation $location
     * @param array $tags
     * @param InstagramMediaPreview $lowResolutionVideo
     * @param InstagramMediaPreview $standardResolutionVideo
     */
    public function __construct($media_id, $commentsCount, $likeCount, InstagramCaption $caption, $permalink, $created, InstagramMediaPreview $thumbnail, InstagramMediaPreview $lowResolutionImage, InstagramMediaPreview $standardResolutionImage, array $usersInMedia, $filter, InstagramLocation $location, array $tags, InstagramMediaPreview $lowResolutionVideo, InstagramMediaPreview $standardResolutionVideo) {
        parent::__construct($media_id, $commentsCount, $likeCount, $caption, $permalink, $created, $thumbnail, $lowResolutionImage, $standardResolutionImage, $usersInMedia, $filter, $location, $tags);
        $this->lowResolutionVideo = $lowResolutionVideo;
        $this->standardResolutionVideo = $standardResolutionVideo;
    }

    /**
     * Get standard resolution video.
     *
     * @return InstagramMediaPreview
     */
    public function getStandardResolutionVideo() {
        return $this->standardResolutionVideo;
    }

    /**
     * Set standard resolution video.
     *
     * @param InstagramMediaPreview $standardResolutionVideo
     */
    public function setStandardResolutionVideo($standardResolutionVideo) {
        $this->standardResolutionVideo = $standardResolutionVideo;
    }

    /**
     * Get low resolution video.
     *
     * @return InstagramMediaPreview
     */
    public function getLowResolutionVideo() {
        return $this->lowResolutionVideo;
    }

    /**
     * Set low resolution video.
     *
     * @param InstagramMediaPreview $lowResolutionVideo
     */
    public function setLowResolutionVideo($lowResolutionVideo) {
        $this->lowResolutionVideo = $lowResolutionVideo;
    }

}