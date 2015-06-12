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
     * Get template path
     *
     * @return string
     */
    public function getTemplatePaths()
    {
        $templatePaths = explode(PHP_EOL, preg_replace('/\R/', PHP_EOL, Mage::getStoreConfig('design/aoe_templateimport/template_paths')));
        return $templatePaths;
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

}
