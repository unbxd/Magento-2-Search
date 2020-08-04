<?php
declare(strict_types=1);

namespace Unbxd\SearchJs\Observer\Backend\Admin;

use Unbxd\SearchJs\Helper\UnbxdConfigHelper;

class SystemSearchJs implements \Magento\Framework\Event\ObserverInterface
{

    const XML_PATH_SEARCH_JS = 'search_js/status/enable';

   public function __construct(
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       \Magento\Config\Model\ResourceModel\Config $resourceConfig
       )
   {
      $this->scopeConfig = $scopeConfig;
      $this->resourceConfig = $resourceConfig;
   }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $status  = $this->scopeConfig->getValue(UnbxdConfigHelper::XML_PATH_AUTOSUGGEST_ENABLED, $storeScope);
        $set_module_state  = ($status == 1) ? 0 : 1;
        $this->resourceConfig->saveConfig('advanced/modules_disable_output/Unbxd_SearchJs', $set_module_state);
    }
}
