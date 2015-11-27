<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramCollection
 *
 * @package jocks\libraries\instagram
 */
class InstagramCollection {

    /**
     * Collection
     *
     * @var array
     */
    private $collection = array();

    /**
     * Collection constructor.
     * @param array $collection
     */
    public function __construct(array $collection = null) {
        if($collection === null)
            $collection = array();
        $this->collection = $collection;
    }

    /**
     * Get full collection.
     *
     * @return array
     */
    public function getCollection() {
        return $this->collection;
    }

    /**
     * Set full collection
     *
     * @param array $collection
     */
    public function setCollection($collection) {
        $this->collection = $collection;
    }

    /**
     * push
     *
     * @param $key
     * @param null $value
     */
    public function push($key, $value = null) {
        if($value === null) {
            $this->collection[] = $key;
        } else {
            $this->collection[$key] = $value;
        }
    }

}