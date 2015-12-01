<?php

// Package
namespace jocks\libraries\instagram;
use jocks\libraries\instagram\exceptions\InstagramException;

// Require
require_once __DIR__ . '/InstagramException.class.php';

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
     * @var string
     */
    private $accessToken;

    /**
     * Instagram maximum rate limit for the application.
     *
     * @var int
     */
    private $ratelimit = 0;

    /**
     * Instagram rate limit remaining. (500/hour in Sandbox mode and 5000/hour in Live mode)
     *
     * @var int
     */
    private $ratelimitRemaining = 0;

    /**
     * Instagram constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @throws InstagramException
     */
    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        // Autoload required files
        if(function_exists('spl_autoload_register')) {
            spl_autoload_register(function($class) {
                if(file_exists(($path = __DIR__ . '/' . basename(end(explode('\\', $class)) . '.class.php')))) {
                    require_once realpath($path);
                }
            });
        } else {
            throw new InstagramException('Unable to initialize the class autoloader.');
        }
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
        $authorizationPath = ($authorizationNeeded === true) ? '?access_token=' . $this->accessToken : '?client_id=' . $this->getApiKey();

        // Create api request
        $apiRequest = self::API_URI . $path . $authorizationPath;

        // Execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        // Modify curl request for each method
        switch($method) {

            // Get method
            case self::METHOD_GET:
                curl_setopt($ch, CURLOPT_URL, $apiRequest . '&' . http_build_query($parameters));
                break;

            // Post method
            case self::METHOD_POST:
                $parameters['sig'] = $this->sign_header($path, $parameters);
                curl_setopt($ch, CURLOPT_URL, $apiRequest);
                curl_setopt($ch,CURLOPT_POST, count($parameters));
                curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;

        }

        // Get result of request
        if(($result = curl_exec($ch)) !== false) {
            list($headers, $json) = explode("\r\n\r\n", $result, 2);
            $headers = $this->http_parse_headers($headers);
            $json = json_decode($json);

            // Set ratelimit
            if(array_key_exists('X-Ratelimit-Limit', $headers)) $this->setRatelimit(intval($headers['X-Ratelimit-Limit']));
            if(array_key_exists('X-Ratelimit-Remaining', $headers)) $this->setRatelimitRemaining(intval($headers['X-Ratelimit-Remaining']));

            // Check for api error
            if(isset($json->meta->error_message)) {
                throw new InstagramException($json->meta->error_message, $json->meta->code);
            }


            // Return json
            return $json;

        } else {
            throw new InstagramException('Request failed with a curl error (ERR: ' . curl_error($ch) . ')');
        }

    }

    /**
     * Sign header for secured requests.
     *
     * @param $endpoint
     * @param array $params
     * @return string
     */
    private function sign_header($endpoint, array $params = array()) {
        ksort($params);
        foreach ($params as $key => $val)
            $endpoint .= '|' . $key . '=' . $val;
        return hash_hmac('sha256', $endpoint, $this->getApiSecret(), false);
    }

    /**
     * Parse http header. Except of the default perl function.
     *
     * @param $raw_headers
     * @return array
     */
    private function http_parse_headers($raw_headers) {
        $headers = array();
        $key = '';

        foreach(explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } else if (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                } else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }

                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } else if(!$key) {
                    $headers[0] = trim($h[0]);
                    trim($h[0]);
                }
            }
        }

        return $headers;
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
     * Get the maximum instagram rate limit.
     *
     * @return int
     */
    public function getRatelimit() {
        return $this->ratelimit;
    }

    /**
     * Set the maximum rate limit.
     *
     * @param int $ratelimit
     */
    public function setRatelimit($ratelimit) {
        $this->ratelimit = $ratelimit;
    }

    /**
     * Get the remaining rate limit.
     *
     * @return int
     */
    public function getRatelimitRemaining() {
        return $this->ratelimitRemaining;
    }

    /**
     * Set the remaining rate limit.
     *
     * @param int $ratelimitRemaining
     */
    public function setRatelimitRemaining($ratelimitRemaining) {
        $this->ratelimitRemaining = $ratelimitRemaining;
    }

    /**
     * Get instagram access token of current session.
     *
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Set instagram access token of current session.
     *
     * @param InstagramOAuthToken|string $accessToken
     */
    public function setAccessToken($accessToken) {
        if($accessToken instanceof InstagramOAuthToken) {
            $this->accessToken = $accessToken->getAccessToken();
        } else {
            $this->accessToken = $accessToken;
        }
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

    /**
     * Get my current media feed.
     *
     * @param null $limit
     * @param null $minId
     * @param null $maxId
     * @return array
     * @throws InstagramException
     */
    public function getMyFeed($limit = null, $minId = null, $maxId = null) {
        return $this->getUserFeed('self', $limit, $minId, $maxId);
    }

    /**
     * Get my liked media.
     *
     * @param null $limit
     * @param null $maxId
     * @return array
     * @throws InstagramException
     */
    public function getMyLikes($limit = null, $maxId = null) {
        $request = $this->apiRequest('/users/self/media/liked/', true, array(
            'count' => $limit,
            'max_like_id' => $maxId
        ));

        return $this->parseMedia($request->data);
    }

    /**
     * Get currently logged in users profile.
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
     * Get users media feed.
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

        return $this->parseMedia($request->data);
    }

    /**
     * Parse requested data to objectives.
     *
     * @param array $data
     * @return array
     */
    private function parseMedia(array $data) {
        $media = array();

        foreach($data as $mediaData) {
            switch($mediaData->type) {

                case 'image':
                    $media[] = new InstagramPhoto(
                        ((isset($mediaData->id)) ? $mediaData->id : null),
                        ((isset($mediaData->comments->count)) ? $mediaData->comments->count : null),
                        ((isset($mediaData->likes->count)) ? $mediaData->likes->count : null),
                        new InstagramCaption(
                            ((isset($mediaData->caption->id)) ? $mediaData->caption->id : null),
                            ((isset($mediaData->caption->username)) ? $mediaData->caption->username : null),
                            ((isset($mediaData->caption->full_name)) ? $mediaData->caption->full_name : null),
                            ((isset($mediaData->caption->type)) ? $mediaData->caption->type : null)
                        ),
                        ((isset($mediaData->link)) ? $mediaData->link : null),
                        ((isset($mediaData->created_time)) ? $mediaData->created_time : null),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->thumbnail->url)) ? $mediaData->images->thumbnail->url : null),
                            ((isset($mediaData->images->thumbnail->width)) ? $mediaData->images->thumbnail->width : null),
                            ((isset($mediaData->images->thumbnail->height)) ? $mediaData->images->thumbnail->height : null)
                        ),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->low_resolution->url)) ? $mediaData->images->low_resolution->url : null),
                            ((isset($mediaData->images->low_resolution->width)) ? $mediaData->images->low_resolution->width : null),
                            ((isset($mediaData->images->low_resolution->height)) ? $mediaData->images->low_resolution->height : null)
                        ),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->standard_resolution->url)) ? $mediaData->images->standard_resolution->url : null),
                            ((isset($mediaData->images->standard_resolution->width)) ? $mediaData->images->standard_resolution->width : null),
                            ((isset($mediaData->images->standard_resolution->height)) ? $mediaData->images->standard_resolution->height : null)
                        ),
                        new InstagramUserCollection((isset($mediaData->users_in_photo)) ? $mediaData->users_in_photo : null),
                        ((isset($mediaData->filter)) ? $mediaData->filter : null),
                        ((isset($mediaData->location)) ? new InstagramLocation(
                            (isset($mediaData->location->id)) ? $mediaData->location->id : null,
                            (isset($mediaData->location->latitude)) ? $mediaData->location->latitude : null,
                            (isset($mediaData->location->longitude)) ? $mediaData->location->longitude : null,
                            (isset($mediaData->location->street_address)) ? $mediaData->location->street_address : null,
                            (isset($mediaData->location->name)) ? $mediaData->location->name : null
                        ) : new InstagramLocation()),
                        ((isset($mediaData->tags)) ? $mediaData->tags : null)
                    );
                    break;

                case 'video':
                    $media[] = new InstagramVideo(
                        ((isset($mediaData->id)) ? $mediaData->id : null),
                        ((isset($mediaData->comments->count)) ? $mediaData->comments->count : null),
                        ((isset($mediaData->likes->count)) ? $mediaData->likes->count : null),
                        new InstagramCaption(
                            ((isset($mediaData->caption->id)) ? $mediaData->caption->id : null),
                            ((isset($mediaData->caption->username)) ? $mediaData->caption->username : null),
                            ((isset($mediaData->caption->full_name)) ? $mediaData->caption->full_name : null),
                            ((isset($mediaData->caption->type)) ? $mediaData->caption->type : null)
                        ),
                        ((isset($mediaData->link)) ? $mediaData->link : null),
                        ((isset($mediaData->created_time)) ? $mediaData->created_time : null),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->thumbnail->url)) ? $mediaData->images->thumbnail->url : null),
                            ((isset($mediaData->images->thumbnail->width)) ? $mediaData->images->thumbnail->width : null),
                            ((isset($mediaData->images->thumbnail->height)) ? $mediaData->images->thumbnail->height : null)
                        ),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->low_resolution->url)) ? $mediaData->images->low_resolution->url : null),
                            ((isset($mediaData->images->low_resolution->width)) ? $mediaData->images->low_resolution->width : null),
                            ((isset($mediaData->images->low_resolution->height)) ? $mediaData->images->low_resolution->height : null)
                        ),
                        new InstagramMediaPreview(
                            ((isset($mediaData->images->standard_resolution->url)) ? $mediaData->images->standard_resolution->url : null),
                            ((isset($mediaData->images->standard_resolution->width)) ? $mediaData->images->standard_resolution->width : null),
                            ((isset($mediaData->images->standard_resolution->height)) ? $mediaData->images->standard_resolution->height : null)
                        ),
                        new InstagramUserCollection((isset($mediaData->users_in_photo)) ? $mediaData->users_in_photo : null),
                        ((isset($mediaData->filter)) ? $mediaData->filter : null),
                        ((isset($mediaData->location)) ? new InstagramLocation(
                            (isset($mediaData->location->id)) ? $mediaData->location->id : null,
                            (isset($mediaData->location->latitude)) ? $mediaData->location->latitude : null,
                            (isset($mediaData->location->longitude)) ? $mediaData->location->longitude : null,
                            (isset($mediaData->location->street_address)) ? $mediaData->location->street_address : null,
                            (isset($mediaData->location->name)) ? $mediaData->location->name : null
                        ) : new InstagramLocation()),
                        ((isset($mediaData->tags)) ? $mediaData->tags : null),
                        new InstagramMediaPreview(
                            ((isset($mediaData->videos->low_resolution->url)) ? $mediaData->videos->low_resolution->url : null),
                            ((isset($mediaData->videos->low_resolution->width)) ? $mediaData->videos->low_resolution->width : null),
                            ((isset($mediaData->videos->low_resolution->height)) ? $mediaData->videos->low_resolution->height : null)
                        ),
                        new InstagramMediaPreview(
                            ((isset($mediaData->videos->standard_resolution->url)) ? $mediaData->videos->standard_resolution->url : null),
                            ((isset($mediaData->videos->standard_resolution->width)) ? $mediaData->videos->standard_resolution->width : null),
                            ((isset($mediaData->videos->standard_resolution->height)) ? $mediaData->videos->standard_resolution->height : null)
                        )
                    );
                    break;
            }
        }

        return $media;
    }

    /**
     * Get someones user id by username
     *
     * @param $username
     * @return int
     * @throws InstagramException
     */
    public function getUserId($username) {
        $request = $this->apiRequest('/users/search/', true, array('count' => 1, 'q' => $username));
        if(count($request->data) === 0)
            throw new InstagramException('User does not exists.');
        return intval($request->data[0]->id);
    }

}
