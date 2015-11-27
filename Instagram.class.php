<?php

// Package
namespace jocks\libraries\instagram;
use jocks\libraries\instagram\exceptions\InstagramException;

/**
 * Class Instagram
 *
 * @package jocks\libraries
 */
class Instagram {

    /**
     * Instagram general api url
     */
    const API_URI = 'https://api.instagram.com/v1/';

    /**
     * Instagram api oauth authorize url
     */
    const API_OAUTH_URI = 'https://api.instagram.com/oauth/authorize/';

    /**
     * Instagram api oauth token service url
     */
    const API_OAUTH_TOKEN_URI = 'https://api.instagram.com/oauth/access_token';

    /**
     * Response get method
     */
    const METHOD_GET = 'GET';

    /**
     * Response post method
     */
    const METHOD_POST = 'POST';

    /**
     * Response delete method
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * Current applications api key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Current applications api secret.
     *
     * @var string
     */
    private $apiSecret;

    /**
     * Access token of current session.
     *
     * @var InstagramOAuthToken
     */
    private $accessToken;

    /**
     * Instagram constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        // Autoload required files
        spl_autoload_register(function($class) {
            if(file_exists(($path = __DIR__ . '/' . basename($class . '.class.php')))) {
                require_once realpath($path);
            }
        });
    }

    /**
     * Get login url for login with instagram
     *
     * @param string $callback
     * @param array $scopes
     * @param string $type
     * @return string
     */
    public function getLoginUrl($callback, array $scopes = array('basic'), $type = 'code') {
        return self::API_OAUTH_URI . '?client_id=' . $this->getApiKey() . '&redirect_uri=' . urlencode($callback) . '&scope=' . implode('+', $scopes) . '&response_type=' . $type;
    }

    /**
     * Get access token for further operations after a successful login
     *
     * @param $callback
     * @param $code
     * @return InstagramOAuthToken
     * @throws InstagramException
     */
    public function getToken($callback, $code) {

        // Set request parameters
        $params = array(
            'grant_type' => 'authorization_code',
            'client_id' => $this->getApiKey(),
            'client_secret' => $this->getApiSecret(),
            'redirect_uri' => $callback,
            'code' => $code
        );

        // Execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_OAUTH_TOKEN_URI);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        $result = json_decode(curl_exec($ch));

        // Check for an error
        if(property_exists($result, 'error_type'))
            throw new InstagramException($result->error_message, $result->code, $result->error_type);

        // Return oauth token
        return new InstagramOAuthToken(
            $result->access_token,
            new InstagramUser(
                intval($result->user->id),
                $result->user->username,
                $result->user->full_name,
                $result->user->bio,
                $result->user->website,
                $result->user->profile_picture
            )
        );

    }

    /**
     * Call the instagram rest api.
     *
     * @param $path
     * @param bool $authorizationNeeded
     * @param array $parameters
     * @param $method
     * @return mixed
     * @throws InstagramException
     */
    private function apiRequest($path, $authorizationNeeded = false, array $parameters = array(), $method = self::METHOD_GET) {

        // Fix path
        if(substr($path, 0, 1) === '/')
            $path = substr($path, 1);

        // Create authorization path
        if($authorizationNeeded === true && $this->accessToken === null)
            throw new InstagramException('You need a valid access token to run this request.');
        $authorizationPath = ($authorizationNeeded === true) ? '?access_token=' . $this->accessToken->getAccessToken() : '?client_id=' . $this->getApiKey();

        // Create api request
        $apiRequest = self::API_URI . $path . $authorizationPath;

        // Execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiRequest);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        // Get result of request
        if(($result = curl_exec($ch)) !== false) {
            list($headers, $json) = explode("\r\n\r\n", $result, 2);
        } else {
            throw new InstagramException('Request failed with a curl error (ERR: ' . curl_error($ch) . ')');
        }

        // Return result
        return json_decode($json);
    }

    /**
     * Get api key for the current application.
     *
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Set api key for the current application.
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Get api secret for the current application.
     *
     * @return string
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

    /**
     * Set api secret for the current application.
     *
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }

    /**
     * Get instagram access token of current session.
     *
     * @return InstagramOAuthToken
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Set instagram access token of current session.
     *
     * @param InstagramOAuthToken $accessToken
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * Get currently logged in users profile
     *
     * @return InstagramUser
     * @throws InstagramException
     */
    public function getMe() {
        return $this->getUser('self');
    }

    public function getMyFeed($limit = null, $minId = null, $maxId = null) {
        return $this->getUserFeed('self', $limit, $minId, $maxId);
    }

    /**
     * Get currently logged in users profile
     *
     * @param int|string $userId
     * @return InstagramUser
     * @throws InstagramException
     */
    public function getUser($userId) {
        $request = $this->apiRequest('/users/' . $userId . '/', true);
        return new InstagramUser(
            intval($request->data->id),
            $request->data->username,
            $request->data->full_name,
            $request->data->bio,
            $request->data->website,
            $request->data->profile_picture,
            $request->data->counts->media,
            $request->data->counts->follows,
            $request->data->counts->followed_by
        );
    }

    /**
     * getUserFeed
     *
     * @param $userId
     * @param null $limit
     * @param null $minId
     * @param null $maxId
     * @return array
     * @throws InstagramException
     */
    public function getUserFeed($userId, $limit = null, $minId = null, $maxId = null) {
        $request = $this->apiRequest('/users/' . $userId . '/media/recent/', true, array(
            'count' => $limit,
            'min_id' => $minId,
            'max_id' => $maxId
        ));

        $media = array();
        foreach($request->data as $mediaData) {
            switch($mediaData->type) {
                case 'image':
                    $media[] = new InstagramPhoto(
                        $mediaData->id,
                        $mediaData->comments->count,
                        $mediaData->likes->count,
                        new InstagramCaption($mediaData->caption->id, $mediaData->caption->username, $mediaData->caption->full_name, $mediaData->caption->type),
                        $mediaData->link,
                        $mediaData->created_time,
                        new InstagramMediaPreview($mediaData->images->thumbnail->url, $mediaData->images->thumbnail->width, $mediaData->images->thumbnail->height),
                        new InstagramMediaPreview($mediaData->images->low_resolution->url, $mediaData->images->low_resolution->width, $mediaData->images->low_resolution->height),
                        new InstagramMediaPreview($mediaData->images->standard_resolution->url, $mediaData->images->standard_resolution->width, $mediaData->images->standard_resolution->height),
                        new InstagramUserCollection($mediaData->users_in_photo),
                        $mediaData->filter,
                        (isset($mediaData->location)) ? new InstagramLocation(
                            (isset($mediaData->location->id)) ? $mediaData->location->id : null,
                            (isset($mediaData->location->latitude)) ? $mediaData->location->latitude : null,
                            (isset($mediaData->location->longitude)) ? $mediaData->location->longitude : null,
                            (isset($mediaData->location->street_address)) ? $mediaData->location->street_address : null,
                            (isset($mediaData->location->name)) ? $mediaData->location->name : null
                        ) : new InstagramLocation(),
                        $mediaData->tags
                    );
                    break;
                case 'video':
                    $media[] = new InstagramVideo(
                        $mediaData->id,
                        $mediaData->comments->count,
                        $mediaData->likes->count,
                        new InstagramCaption($mediaData->caption->id, $mediaData->caption->username, $mediaData->caption->full_name, $mediaData->caption->type),
                        $mediaData->link,
                        $mediaData->created_time,
                        new InstagramMediaPreview($mediaData->images->thumbnail->url, $mediaData->images->thumbnail->width, $mediaData->images->thumbnail->height),
                        new InstagramMediaPreview($mediaData->images->low_resolution->url, $mediaData->images->low_resolution->width, $mediaData->images->low_resolution->height),
                        new InstagramMediaPreview($mediaData->images->standard_resolution->url, $mediaData->images->standard_resolution->width, $mediaData->images->standard_resolution->height),
                        new InstagramUserCollection($mediaData->users_in_photo),
                        $mediaData->filter,
                        (isset($mediaData->location)) ? new InstagramLocation(
                            (isset($mediaData->location->id)) ? $mediaData->location->id : null,
                            (isset($mediaData->location->latitude)) ? $mediaData->location->latitude : null,
                            (isset($mediaData->location->longitude)) ? $mediaData->location->longitude : null,
                            (isset($mediaData->location->street_address)) ? $mediaData->location->street_address : null,
                            (isset($mediaData->location->name)) ? $mediaData->location->name : null
                        ) : new InstagramLocation(),
                        $mediaData->tags,
                        new InstagramMediaPreview($mediaData->videos->low_resolution->url, $mediaData->videos->low_resolution->width, $mediaData->videos->low_resolution->height),
                        new InstagramMediaPreview($mediaData->videos->standard_resolution->url, $mediaData->videos->standard_resolution->width, $mediaData->videos->standard_resolution->height)
                    );
                    break;
            }
        }

        // Return media
        return $media;
    }

}
