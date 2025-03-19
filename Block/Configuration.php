<?php

namespace Unbxd\SearchJs\Block;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\CollectionDataSourceInterface;
use Magento\Framework\DataObject;
use Unbxd\SearchJs\Helper\UnbxdConfigHelper;

class Configuration extends Unbxd implements CollectionDataSourceInterface
{

    public function isAutoSuggestEnabled(){
       $enabled= $this->getUnbxdConfigHelper()->isAutoSuggestEnabled($this->getStoreId());
       $config= $this->getUnbxdConfigHelper()->getAutosuggestConfigObject($this->getStoreId());
       return $enabled && !empty($config) && $this->isKeySetup();
    }

    public function getAutoSuggestConfig(){
        
        return $this->getUnbxdConfigHelper()->getAutosuggestConfigObject($this->getStoreId());
     }

    public function isAnalyticsEnabled(){
       $enabled= $this->getUnbxdConfigHelper()->isAnalyticsEnabled($this->getStoreId());
       return $enabled  && $this->isKeySetup();;
    }

    public function isRecommendationEnabled(){
        $enabled= $this->getUnbxdConfigHelper()->isRecommendationEnabled($this->getStoreId());
        return $enabled  && $this->isKeySetup() && !empty($this->getUnbxdConfigHelper()->getRecommendationSdkUrl($this->getStoreId()));
     }
 

    public function isSearchEnabled(){
        $enabled= $this->getUnbxdConfigHelper()->isSearchEnabled($this->getStoreId());;
        $config= $this->getUnbxdConfigHelper()->getSearchConfigObject($this->getStoreId());
        return $enabled && !empty($config) && $this->isKeySetup() && "catalogsearch_result_index" == $this->getLayoutName();
     }

     public function getSearchConfig(){
        
        return $this->getUnbxdConfigHelper()->getSearchConfigObject($this->getStoreId());
     }

     public function isCategoryEnabled(){
        $enabled= $this->getUnbxdConfigHelper()->isCategoryEnabled($this->getStoreId());;
        $config= $this->getUnbxdConfigHelper()->getCategoryConfigObject($this->getStoreId());
        return $enabled && !empty($config) && $this->isKeySetup() && "catalog_category_view" == $this->getLayoutName();
     }

     public function getCategoryConfig(){
        
        return $this->getUnbxdConfigHelper()->getCategoryConfigObject($this->getStoreId());
     }


    private function isKeySetup(){
        return !empty($this->getUnbxdConfigHelper()->getSiteName($this->getStoreId())) && !empty($this->getUnbxdConfigHelper()->getApiKey($this->getStoreId()));
    }

    

    public function getConfiguration(){

        $unbxd_config = [
            'credentials' => [
                'siteName' => $this->getUnbxdConfigHelper()->getSiteName($this->getStoreId()),
                'apiKey' => $this->getUnbxdConfigHelper()->getApiKey($this->getStoreId())
            ],
            'autoSuggest' => [
                'customTemplate' => $this->getUnbxdConfigHelper()->isCustomTemplateInUse($this->getStoreId()),
                'searchInputSelector' => $this->getUnbxdConfigHelper()->getSearchSelector($this->getStoreId()),
                'jsUrl' => $this->getUnbxdConfigHelper()->getAutoSuggestJsUrl(),
                'cssUrl' => $this->getUnbxdConfigHelper()->getAutoSuggestCSSUrl()
            ],
            'search' => [
                'jsUrl' => $this->getUnbxdConfigHelper()->getSearchJSUrl(),
                'cssUrl' => $this->getUnbxdConfigHelper()->getSearchCSSUrl()

            ],
            'analytics' => [
                'v2Analytics' => $this->getUnbxdConfigHelper()->isAnalyticsV2Enabled(),
                'sdkUrl' => $this->getUnbxdConfigHelper()->getAnalyticsSDKUrl(),
                'jsUrl' => $this->getUnbxdConfigHelper()->isAnalyticsV2Enabled() ?$this->getUnbxdConfigHelper()->getAnalyticsImplUrl() : $this->getUnbxdConfigHelper()->getAnalyticsJSUrl(),
                'productId' => $this->getProductId(),
                'cartBtnSelector' => $this->getUnbxdConfigHelper()->getAddToCartSelector(),
                'removeCartItemSelector' => $this->getUnbxdConfigHelper()->getRemoveCartSelector(),
                'orderConversionEntities' => $this->getLastOrderEntities()
            ],
            'recommendation' => [
                'sdkUrl' => $this->getUnbxdConfigHelper()->getRecommendationSdkUrl()
            ]
        ];

        return $unbxd_config;

    }


    private function getProductId(){
        if ($this->getLayoutName() === 'catalog_product_view' && $this->getCurrentProduct()) {
            return $this->getCurrentProduct()->getId();
        }
            return null;
    }

    private function getLastOrderEntities(){
        $entities = [];

        if ($this->getRequest()->getFrontName() !== 'checkout'
            || $this->getRequest()->getActionName() !== 'success') {
            return $entities;
        }

        $lastOrder = $this->getLastOrder();
        if (!$lastOrder) {
            return $entities;
        }

        $items = $lastOrder->getItems();
        foreach ($items as $item) {
           // $entity["variantId"] = $item->getSku();
            $entity["qty"] = $item->getQtyOrdered();
            $entity["price"] = $item->getBasePrice();
            $entity["pid"] = $item->getProductId();
            $entities[]=$entity;
        }

        return $entities;

    }

}