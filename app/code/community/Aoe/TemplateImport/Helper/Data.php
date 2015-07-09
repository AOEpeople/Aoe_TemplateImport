<?php

/**
 * Data helper
 *
 * @author Manish Jain
 */
class Aoe_TemplateImport_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get template path
     *
     * @return string
     */
    public function isEnabled() {
        return Mage::getStoreConfig('design/aoe_templateimport/enabled');
    }

    /**
     * Get template config
     *
     * @return string
     */
    public function getTemplateConfig()
    {
        $configString = Mage::getStoreConfig('design/aoe_templateimport/template_paths');
        $lines = $this->trimExplode("\n", $configString, true);
        $config = array();
        foreach ($lines as $line) {
            list($pattern, $path, $basepath, $lifetime) = $this->trimExplode(';', $line);
            $config[$pattern] = array(
                'path' => $this->replacePlaceholders($path),
                'lifetime' => $lifetime,
                'basepath' => $this->replacePlaceholders($basepath)
            );
        }
        return $config;
    }

    /**
     * Replaces:
     * ###BASE_URL###
     * ###MAGENTO_ROOT###
     *
     * @var string $string
     * @return string
     */
    protected function replacePlaceholders($string)
    {
        $replace = array(
            '###BASE_URL###' => Mage::getBaseUrl(),
            '###MAGENTO_ROOT###' => MAGENTO_ROOT
        );
        return str_replace(array_keys($replace), array_values($replace), $string);
    }

    /**
     * Get http authentication username
     *
     * @return string
     */
    public function getHttpUsername()
    {
        return Mage::getStoreConfig('design/aoe_templateimport/http_username');
    }

    /**
     * Get http authentication username
     *
     * @return string
     */
    public function getHttpPassword()
    {
        return Mage::getStoreConfig('design/aoe_templateimport/http_password');
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
