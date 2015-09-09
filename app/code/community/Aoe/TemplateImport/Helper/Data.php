<?php

/**
 * TemplateImport default helper
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Fabrizio Branca
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    /**
     * Replace variables...
     *
     * @param $string
     * @return string
     * @throws Exception
     */
    public function filter($string)
    {
        if (strpos($string, '{{') !== false) {
            $helper = Mage::helper('cms'); /* @var Mage_Cms_Helper_Data $helper */
            $processor = $helper->getBlockTemplateProcessor();
            $string = $processor->filter($string);
        }
        $replace = array(
            '###BASE_URL###' => Mage::getBaseUrl(),
            '###MAGENTO_ROOT###' => Mage::getBaseDir()
        );
        return str_replace(array_keys($replace), array_values($replace), $string);
    }

}
