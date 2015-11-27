<?php

// Package
namespace jocks\libraries\instagram;

/**
 * Class InstagramUserCollection
 *
 * @package jocks\libraries\instagram
 */
class InstagramUserCollection extends InstagramCollection {

    /**
     * UserCollection constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection) {
        $reformed = array();
        foreach($collection as $key => $element) {
            $reformed[$key] = new InstagramMediaTaggedUser(
                $element->user->id,
                $element->user->username,
                $element->user->full_name,
                $element->user->profile_picture,
                $element->position->x,
                $element->position->y
            );
        }
        $collection = $reformed;
        parent::__construct($collection);
    }

}