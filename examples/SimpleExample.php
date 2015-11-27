<?php

// Require
require_once dirname(__DIR__) . '/Instagram.class.php';
use jocks\libraries\instagram\Instagram;
use \jocks\libraries\instagram\exceptions\InstagramException;

// Configuration
$apiKey = 'YOUR-KEY';
$apiSecret = 'YOUR-SECRET';
$apiCallback = 'YOUR-CALLBACK';
$appPermissions = array('basic', 'public_content', 'follower_list', 'comments', 'relationships', 'likes');
/*  == Available permissions are: ==
    :: basic - to read a user's profile info and media (granted by default)
    :: public_content - to read any public profile info and media on a user's behalf
    :: follower_list - to read the list of followers and followed-by users
    :: comments - to post and delete comments on a user's behalf
    :: relationships - to follow and unfollow accounts on a user's behalf
    :: likes - to like and unlike media on a user's behalf
 */

// Init instagram instance
$instagram = new Instagram($apiKey, $apiSecret);
echo '<a href="' . $instagram->getLoginUrl($apiCallback, $appPermissions) . '">Login with instagram</a><br /><br /><br />';

// Proceed the returning request
if(array_key_exists('code', $_GET)) {
    try {
        $apiToken = $instagram->getToken($apiCallback, $_GET['code']);
        $instagram->setAccessToken($apiToken);

        $myAccount = $instagram->getMe();
        $myFeed = $instagram->getMyFeed();
        $someonesFeed = $instagram->getUserFeed(21183801);
        var_dump($someonesFeed);

    } catch(InstagramException $ex) {
        die('<strong>Error:</strong> <i>' . $ex->getMessage() . '</i>');
    }
}