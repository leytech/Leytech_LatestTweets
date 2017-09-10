<?php
/**
 * @package    Leytech_LatestTweets
 * @author     Chris Nolan (chris@leytech.co.uk)
 * @copyright  Copyright (c) 2017 Leytech
 * @license    https://opensource.org/licenses/MIT  The MIT License  (MIT)
 */
class Leytech_LatestTweets_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $module_enabled;
    protected $cache_lifetime;

    protected $twitter_id;
    protected $consumer_key;
    protected $consumer_secret;
    protected $oauth_access_token;
    protected $oauth_access_token_secret;

    function __construct()
    {
        // !TODO changing the include path is a bit naughty in here
        // could maybe use an observer instead
        // seee: http://davemacaulay.com/how-to-include-composer-libraries-within-magento/
        set_include_path(get_include_path() . PATH_SEPARATOR . Mage::getBaseDir('lib') . DS . 'Leytech' . DS . 'LatestTweets' . DS . 'TwitterAPIExchange');
        set_include_path(get_include_path() . PATH_SEPARATOR . Mage::getBaseDir('lib') . DS . 'Leytech' . DS . 'LatestTweets' . DS . 'TimeAgo');

        // set the variables as per admin config
        $this->module_enabled = ( Mage::getStoreConfig('leytech_latesttweets/general/enabled') ? true : false);
        $this->cache_lifetime = Mage::getStoreConfig('leytech_latesttweets/general/cache_lifetime');

        $this->twitter_id = Mage::getStoreConfig('leytech_latesttweets/api_settings/twitterid');
        $this->consumer_key = Mage::getStoreConfig('leytech_latesttweets/api_settings/consumerkey');
        $this->consumer_secret = Mage::getStoreConfig('leytech_latesttweets/api_settings/consumersecret');
        $this->oauth_access_token = Mage::getStoreConfig('leytech_latesttweets/api_settings/accesstoken');
        $this->oauth_access_token_secret = Mage::getStoreConfig('leytech_latesttweets/api_settings/accesstokensecret');

    }

    public function isModuleEnabled() {
        return $this->module_enabled;
    }

}