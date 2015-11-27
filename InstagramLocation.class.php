<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramLocation
 *
 * @package jocks\libraries\instagram
 */
class InstagramLocation {

    /**
     * Internal id of this location.
     *
     * @var int
     */
    private $locationId;

    /**
     * Horizontal position of this location.
     *
     * @var float
     */
    private $lat;

    /**
     * Vertical position of this location.
     *
     * @var float
     */
    private $lng;

    /**
     * Street address of this location.
     *
     * @var string
     */
    private $streetAddress;

    /**
     * Alternative name of this location.
     *
     * @var string
     */
    private $name;

    /**
     * InstagramLocation constructor.
     *
     * @param int $locationId
     * @param $lat
     * @param $lng
     * @param string $streetAddress
     * @param string $name
     */
    public function __construct($locationId = null, $lat = null, $lng = null, $streetAddress = null, $name = null) {
        $this->locationId = $locationId;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->streetAddress = $streetAddress;
        $this->name = $name;
    }

    /**
     * Get internal location id.
     *
     * @return int
     */
    public function getLocationId() {
        return $this->locationId;
    }

    /**
     * Set internal id of this location.
     *
     * @param int $locationId
     */
    public function setLocationId($locationId) {
        $this->locationId = $locationId;
    }

    /**
     * Get horizontal position of this location.
     *
     * @return float
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set horizontal position of this location.
     *
     * @param float $lat
     */
    public function setLat($lat) {
        $this->lat = $lat;
    }

    /**
     * Get vertical position of this location.
     *
     * @return float
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set vertical position of this location.
     *
     * @param float $lng
     */
    public function setLng($lng) {
        $this->lng = $lng;
    }

    /**
     * Get street address of this location.
     *
     * @return string
     */
    public function getStreetAddress() {
        return $this->streetAddress;
    }

    /**
     * Set street address of this location.
     *
     * @param string $streetAddress
     */
    public function setStreetAddress($streetAddress) {
        $this->streetAddress = $streetAddress;
    }

    /**
     * Get alternative name of this location.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set alternative name of this location.
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

}