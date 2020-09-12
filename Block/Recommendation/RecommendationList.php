<?php

namespace Unbxd\SearchJs\Block\Recommendation;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart as CartModel;

class RecommendationList extends Template implements BlockInterface
{

    private $registry;
    private $catalogHelper;
    private $formKey;
    private $cartModel;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Registry $registry,
        FormKey $formKey,
        CatalogHelper $catalogHelper,
        CartModel $cartModel,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->catalogHelper = $catalogHelper;
        $this->formKey = $formKey;
        $this->cartModel = $cartModel;
        parent::__construct($context,$data);
    }
    protected $_template = "widget/recommendationList.phtml";

    public function isWidgetApplicable(){
        if($this->getData('widget_type')){
        switch($this->getData('widget_type')){
            case 'PRODUCT':
                return !empty($this->catalogHelper->getProduct());
            case 'CART':
                return !empty($this->getCartItems());
            case 'CATEGORY':
                return !empty($this->catalogHelper->getCategory());
            case 'HOME':
                return true;
            default:
                return false;
        }
    }
    return false;
    }

    public function pageInfoForWidget(){
            $pageInfo = [
                'pageType' => $this->getData('widget_type')
            ];
            switch($this->getData('widget_type')){
                case 'PRODUCT':
                    $pageInfo['productIds'] = $this->getCurrentProduct();
                    break;
                case 'CART':
                    $pageInfo['productIds'] = $this->getCartItems();
                    break;
                case 'CATEGORY':
                    $this->populateCategoryParameter($pageInfo);
                    break;
                default:
                    // do notthing
            }

            return $pageInfo;

    }

    public function getFormKey(){
        return $this->formKey->getFormKey();
    }

    public function getCartItems(){

        return $this->cartModel->getProductIds();
    }

    /**
     * Populate the pageinfo with cat names
     *
     */
    public function populateCategoryParameter(&$pageInfo)
    {
        if ($this->catalogHelper->getCategory()) {
            $categoryBreadCrumbPath = $this->catalogHelper->getBreadcrumbPath();
            $count = 1;
            foreach ($categoryBreadCrumbPath as $entry) {
                $pageInfo['catlevel'.$count.'Name'] = $entry['label'];
                $count++;
            } 
        }
    }

    /**
     * Retrieve current product model object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentProduct()
    {
        $productArray = [];
        if ($this->catalogHelper->getProduct()) {
            $productArray[] = $this->catalogHelper->getProduct()->getId();
        }
        return $productArray;
    }


    public function getWidgetId(){
        $pageType = $this->getData('widget_type');
        return $pageType.'-'.$this->generateRandomString();
    }

    private function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

}