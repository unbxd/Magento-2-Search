<?php

namespace Unbxd\SearchJs\Block;

use Unbxd\SearchJs\Helper\UnbxdConfigHelper as UnbxdConfig;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Data\CollectionDataSourceInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Locale\Currency;
use Magento\Framework\Locale\Format;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Search\Helper\Data as CatalogSearchHelper;

class Unbxd extends Template implements CollectionDataSourceInterface
{
    private $unbxdConfig;
    private $catalogSearchHelper;
    private $registry;
    private $currency;
    private $format;
    private $urlHelper;
    private $formKey;
    private $httpContext;
    private $checkoutSession;
    private $date;

    private $priceKey;

    public function __construct(
        Template\Context $context,
        UnbxdConfig $unbxdConfig,
        CatalogSearchHelper $catalogSearchHelper,
        Currency $currency,
        Format $format,
        Registry $registry,
        Data $urlHelper,
        FormKey $formKey,
        HttpContext $httpContext,
        CheckoutSession $checkoutSession,
        DateTime $date,
        array $data = []
    ) {
        $this->catalogSearchHelper = $catalogSearchHelper;
        $this->currency = $currency;
        $this->format = $format;
        $this->registry = $registry;
        $this->urlHelper = $urlHelper;
        $this->formKey = $formKey;
        $this->httpContext = $httpContext;
        $this->checkoutSession = $checkoutSession;
        $this->date = $date;
        $this->unbxdConfig=$unbxdConfig;

        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();

        return $store;
    }

   
    public function getUnbxdConfigHelper()
    {
        return $this->unbxdConfig;
    }

    public function getCatalogSearchHelper()
    {
        return $this->catalogSearchHelper;
    }


    public function getStoreId()
    {
        return $this->getStore()->getStoreId();
    }

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /** @return \Magento\Catalog\Model\Product */
    public function getCurrentProduct()
    {
        return $this->registry->registry('product');
    }

    /** @return \Magento\Sales\Model\Order */
    public function getLastOrder()
    {
        return $this->checkoutSession->getLastRealOrder();
    }

    public function getAddToCartParams()
    {
        $url = $this->getAddToCartUrl();

        return [
            'action' => $url,
            'formKey' => $this->formKey->getFormKey(),
        ];
    }

    public function getTimestamp()
    {
        return $this->date->gmtTimestamp('today midnight');
    }

    private function getAddToCartUrl($additional = [])
    {
        $continueUrl = $this->urlHelper->getEncodedUrl($this->_urlBuilder->getCurrentUrl());
        $urlParamName = ActionInterface::PARAM_NAME_URL_ENCODED;

        $routeParams = [
            $urlParamName => $continueUrl,
            '_secure' => $this->getRequest()->isSecure(),
        ];

        if ($additional !== []) {
            $routeParams = array_merge($routeParams, $additional);
        }

        return $this->_urlBuilder->getUrl('checkout/cart/add', $routeParams);
    }

    protected function getCurrentLandingPage()
    {
        $landingPageId = $this->getRequest()->getParam('landing_page_id');
        if (!$landingPageId) {
            return null;
        }

        return $this->landingPageHelper->getLandingPage($landingPageId);
    }

    public function getLayoutName(){
        return $this->getRequest()->getFullActionName();
    }
}
