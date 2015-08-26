<?php

/**
 * Class Aoe_TemplateImport_Block_Html
 *
 * @author Manish Jain
 * @author Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Html extends Mage_Page_Block_Html
{

    /**
     * Render the page html
     * Renders all placeholders inside the page.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::getStoreConfig('design/aoe_templateimport/enabled')) {
            return parent::_toHtml();
        }

        $fullActionName = $this->getFullActionName();
        $storeId = Mage::app()->getStore()->getId();

        $origin = Mage::getModel('aoe_templateimport/origin'); /* @var $origin Aoe_TemplateImport_Model_Origin */
        $origin->loadByFullActionNameAndStore($fullActionName, $storeId);

        if (!$origin->getId()) {
            Mage::log('[Aoe_TemplateImport] No configuration found for: ' . $fullActionName . ' in store ' . $storeId, Zend_Log::ERR);
            if (Mage::getIsDeveloperMode()) {
                return '[no config found for "' . $fullActionName . ' in store ' . $storeId . '"]';
            } else {
                return '';
            }
        }

        $source = $origin->getSource();

        $sourceHelper = Mage::helper('aoe_templateimport/source'); /* @var $sourceHelper Aoe_TemplateImport_Helper_Source */
        $source = $sourceHelper->injectChildBlocks($source, $this);

        return $source;
    }

    /**
     * Get full actioname
     *
     * @return string
     */
    public function getFullActionName()
    {
        $route = $this->getRequest()->getRouteName();
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        $fullActionName = $route . '_' . $controller . '_' . $action;
        return $fullActionName;
    }

}