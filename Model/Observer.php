<?php

namespace Unbxd\SearchJs\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Store\Model\StoreManagerInterface;
use Unbxd\SearchJs\Helper\UnbxdConfigHelper;

/**
 * Algolia search observer model
 */
class Observer implements ObserverInterface
{
    private $config;
    private $registry;
    private $storeManager;
    private $pageConfig;

    public function __construct(
        UnbxdConfigHelper $configHelper,
        Registry $registry,
        StoreManagerInterface $storeManager,
        PageConfig $pageConfig
    ) {
        $this->config = $configHelper;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->pageConfig = $pageConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $layout = $observer->getData('layout');
        $layout->getUpdate()->addHandle('unbxd_handle');
        if ($this->config->isAutoSuggestEnabled() && !$this->config->isCustomTemplateInUse()) {
            $layout->getUpdate()->addHandle('unbxd_topsearch');

        }

        if ("catalogsearch_result_index" == $observer->getData("full_action_name") && $this->config->isSearchEnabled()) {
            $layout->getUpdate()->addHandle('unbxd_search_handle');
        }

        if ("catalog_category_view" == $observer->getData("full_action_name") && $this->config->isCategoryEnabled()) {
            $category = $this->getCurrentCategory();
            if ($category && $category->getDisplayMode() !== 'PAGE') {
                $layout->getUpdate()->addHandle('unbxd_category_handle');
            }
        }

        if ($this->config->isAnalyticsEnabled()) {
            $layout->getUpdate()->addHandle('unbxd_analytics_handle');
        }
    }

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

}
