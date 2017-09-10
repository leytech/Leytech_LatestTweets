<?php
/**
 * @package    Leytech_LatestTweets
 * @author     Chris Nolan (chris@leytech.co.uk)
 * @copyright  Copyright (c) 2017 Leytech
 * @license    https://opensource.org/licenses/MIT  The MIT License  (MIT)
 */
class Leytech_LatestTweets_Helper_Twitter extends Leytech_LatestTweets_Helper_Data
{
    // some default values
    const RESPONSE_LIMIT            = 5;

    public function getTweets($limit = self::RESPONSE_LIMIT)
    {
        $coreHelper   = Mage::helper('core');
        $username     = trim($this->twitter_id);
        $limit        = intval($limit);
        $cacheKey     = 'twitter_' . md5($username);
        $cacheTags    = array('twitter', $cacheKey);
        $tweets       = array();
        if (!$username || !$limit || $limit <= 0) {
            return array();
        }
        // try to load tweets from cache
        $responseJson = $this->_loadCache($cacheKey . '_' . $limit);
        if (empty($responseJson)) {
            // this is where we instantiate and configure
            // our API interface.
            $twitter = new TwitterAPIExchange(array(
                'oauth_access_token'        => $this->oauth_access_token,
                'oauth_access_token_secret' => $this->oauth_access_token_secret,
                'consumer_key'              => $this->consumer_key,
                'consumer_secret'           => $this->consumer_secret
            ));
            // set our parameters for accessing user_timeline.json
            // see more: https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
            $getParameters = array(
                'screen_name=' . urlencode($username),
                'count=' . urlencode($limit)
            );
            // this is where the request is being made
            $responseJson = $twitter->setGetfield('?' . implode('&', $getParameters))
                ->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET')
                ->performRequest();
            // save tweets to cache
            $this->_saveCache($responseJson,
                $cacheKey . '_' . $limit,
                $cacheTags,
                $this->cache_lifetime);
        }
        // decode JSON response
        $tweets = $coreHelper->jsonDecode($responseJson);
        // set the time_ago for nice times
        $timeAgo = new TimeAgo();
        foreach ($tweets as &$tweet) {
            $tweet['time_ago'] = $timeAgo->inWords($tweet["created_at"]);
        }
        return ($limit == 1 ? $tweets[0] : $tweets);
    }
}