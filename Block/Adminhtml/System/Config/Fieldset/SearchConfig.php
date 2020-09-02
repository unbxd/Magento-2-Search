<?php
/**
 * Copyright (c) 2020 Unbxd Inc.
 */

/**
 * Init development:
 * @author jags
 * @email jagadeesh@oceaniasolution.com
 * @team oceania software services
 */
namespace Unbxd\SearchJs\Block\Adminhtml\System\Config\Fieldset;
use Unbxd\ProductFeed\Block\Adminhtml\System\Config\Fieldset\AbstractFieldset;

/**\
 * Class SearchConfig
 * @package Unbxd\SearchJs\Block\Adminhtml\System\Config\Fieldset
 */
class SearchConfig extends AbstractFieldset
{
    /**
     * @var string
     */
    protected $_template = 'Unbxd_SearchJs::system/config/fieldset/searchjs.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return mixed
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        if ($element->getData('group')['id'] == 'productconfig_header') {
            $html = $this->toHtml();
        }
        return $html;
    }
}