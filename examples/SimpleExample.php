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

try {

    // Init instagram instance
    $instagram = new Instagram($apiKey, $apiSecret);
    echo '<a href="' . $instagram->getLoginUrl($apiCallback, $appPermissions) . '">Login with instagram</a><br /><br /><br />';

    if(array_key_exists('error_description', $_GET)) {
        throw new InstagramException($_GET['error_description']);

    } else if(array_key_exists('code', $_GET)) {
        $apiToken = $instagram->getToken($apiCallback, $_GET['code']);
        header('Location: ' . $apiCallback . '?access=' . $apiToken->getAccessToken());
        exit();

    } else if(array_key_exists('access', $_GET)) {
        $instagram->setAccessToken($_GET['access']);
        $myLikes = $instagram->getMyLikes();
        var_dump($myLikes);
    }

    echo '<br /><br /><br /><i>Rate remaining ' . $instagram->getRatelimitRemaining() . '/' . $instagram->getRatelimit() . '</i>';

} catch(InstagramException $ex) {
    die('<strong>Error:</strong> <i>' . $ex->getMessage() . '</i> (Rate remaining ' . $instagram->getRatelimitRemaining() . '/' . $instagram->getRatelimit() . ')');
}