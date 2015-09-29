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

    /**
     * Explodes a string and trims all values for whitespace in the ends.
     * If $onlyNonEmptyValues is set, then all blank ('') values are removed.
     *
     * @see t3lib_div::trimExplode() in TYPO3
     * @param $delim
     * @param string $string
     * @param bool $removeEmptyValues If set, all empty values will be removed in output
     * @return array Exploded values
     */
    public function trimExplode($delim, $string, $removeEmptyValues = false)
    {
        $explodedValues = explode($delim, $string);

        $result = array_map('trim', $explodedValues);

        if ($removeEmptyValues) {
            $temp = array();
            foreach ($result as $value) {
                if ($value !== '') {
                    $temp[] = $value;
                }
            }
            $result = $temp;
        }

        return $result;
    }

}
