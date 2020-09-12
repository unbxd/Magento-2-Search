<?php

namespace Unbxd\SearchJs\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;



class UnbxdConfigHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SETUP_SITE_KEY = 'unbxd_setup/general/site_key';
    const XML_PATH_SETUP_API_KEY = 'unbxd_setup/general/api_key';
    const XML_PATH_AUTOSUGGEST_ENABLED = 'unbxd_search_config/autosuggest/enable';
    const XML_PATH_ANALYTICSENABLED = 'unbxd_search_config/analytics/enable';
    const XML_PATH_ADDCART_BTN = 'unbxd_search_config/analytics/add_cart_btn_selector';
    const XML_PATH_REMOVECART_BTN = 'unbxd_search_config/analytics/remove_cart_item_selector';
    const XML_PATH_AUTOSUGGEST_CONFIG = 'unbxd_search_config/autosuggest/config_object';
    const XML_PATH_AUTOSUGGEST_CUSTOMTEMPLATE = 'unbxd_search_config/autosuggest/custom_search_template';
    const XML_PATH_AUTOSUGGEST_INPUT_SELECTOR = 'unbxd_search_config/autosuggest/search_input_selector';
    const XML_PATH_SEARCH_CONFIG = 'unbxd_search_config/search/config_object';
    const XML_PATH_SEARCH_ENABLED = 'unbxd_search_config/search/enable';
    const XML_PATH_CATEGORY_CONFIG = 'unbxd_search_config/category/config_object';
    const XML_PATH_CATEGORY_ENABLED = 'unbxd_search_config/category/enable';
    const XML_PATH_RECOMMENDATION_ENABLED = 'unbxd_search_config/recommendation/enable';
    const XML_PATH_RECOMMENDATION_SDK = 'unbxd_search_config/recommendation/rex_sdk_url';

    private $autosuggestCSSUrl;
    private $searchCSSUrl;
    private $autosuggestJSUrl;
    private $searchJSUrl;
    private $analyticsJSUrl;
    

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->assetRepository = $assetRepo;
       
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getSiteName($store = null)
    {
        return trim($this->_scopeConfig->getValue(
            self::XML_PATH_SETUP_SITE_KEY,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }



    /**
     * @param null $store
     * @return mixed
     */
    public function getApiKey($store = null)
    {
        return trim($this->_scopeConfig->getValue(
            self::XML_PATH_SETUP_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }

    public function getConfigValue($path,$storeId = null)
    {
        $value =$this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE,$storeId);
        return empty($value) ? null : trim($value);
    }

    public function isAutoSuggestEnabled($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_AUTOSUGGEST_ENABLED,$storeId);
        return ($status == 1) ? true : false;
    }


    public function getAutosuggestConfigObject($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_AUTOSUGGEST_CONFIG,$storeId);
        
    }

    public function isCustomTemplateInUse($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_AUTOSUGGEST_CUSTOMTEMPLATE,$storeId);
        return ($status == 1) ? true : false;
    }

    public function getSearchSelector($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_AUTOSUGGEST_INPUT_SELECTOR,$storeId);
    }

    public function isSearchEnabled($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_SEARCH_ENABLED,$storeId);
        return ($status == 1) ? true : false;
    }

    public function getRecommendationSdkUrl($storeId = null)
    {
        $selector = $this->getConfigValue(self::XML_PATH_RECOMMENDATION_SDK,$storeId);
        return ($selector) ? $selector : null;
    }

    public function isRecommendationEnabled($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_RECOMMENDATION_ENABLED,$storeId);
        return ($status == 1) ? true : false;
    }


    public function getSearchConfigObject($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SEARCH_CONFIG,$storeId);
        
    }

    public function isCategoryEnabled($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_CATEGORY_ENABLED,$storeId);
        return ($status == 1) ? true : false;
    }


    public function getCategoryConfigObject($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_CATEGORY_CONFIG,$storeId);
        
    }

    public function getAutoSuggestJsUrl()
    {
        if (empty($this->autosuggestJSUrl)) {
            $asset_autosuggest = $this->assetRepository->createAsset('Unbxd_SearchJs::js/autosuggest.js');
            $this->autosuggestJSUrl = $asset_autosuggest->getUrl();
        }
        return ($this->autosuggestJSUrl) ? $this->autosuggestJSUrl : '';
    }

    public function getSearchJSUrl()
    {
        if (empty($this->searchJSUrl)) {
            $asset_search = $this->assetRepository->createAsset('Unbxd_SearchJs::js/search.js');
            $this->searchJSUrl = $asset_search->getUrl();
        }

        return ($this->searchJSUrl) ? $this->searchJSUrl : '';
    }

    public function getAutoSuggestCSSUrl()
    {
        if (empty($this->autosuggestCSSUrl)) {
            $asset_autosuggest = $this->assetRepository->createAsset('Unbxd_SearchJs::css/autosuggest.css');
            $this->autosuggestCSSUrl = $asset_autosuggest->getUrl();
        }
        return ($this->autosuggestCSSUrl) ? $this->autosuggestCSSUrl : '';
    }

    public function getSearchCSSUrl()
    {
        if (empty($this->searchCSSUrl)) {
            $asset_search = $this->assetRepository->createAsset('Unbxd_SearchJs::css/search.css');
            $this->searchCSSUrl = $asset_search->getUrl();
        }

        return ($this->searchCSSUrl) ? $this->searchCSSUrl : '';
    }




    public function isAnalyticsEnabled($storeId = null)
    {
        $status = $this->getConfigValue(self::XML_PATH_ANALYTICSENABLED,$storeId);
        return ($status == 1) ? true : false;
    }



    public function getAddToCartSelector($storeId = null)
    {
        $selector = $this->getConfigValue(self::XML_PATH_ADDCART_BTN,$storeId);
        return ($selector) ? $selector : null;
    }
    
    public function getRemoveCartSelector($storeId = null)
    {
        $selector = $this->getConfigValue(self::XML_PATH_REMOVECART_BTN,$storeId);
        return ($selector) ? $selector : null;
    }

    public function getAnalyticsJSUrl()
    {
        if (empty($this->analyticsJSUrl)) {
            $asset_analytics = $this->assetRepository->createAsset('Unbxd_SearchJs::js/analytics.js');
            $this->analyticsJSUrl = $asset_analytics->getUrl();
        }

        return ($this->analyticsJSUrl) ? $this->analyticsJSUrl : '';
    }

}
