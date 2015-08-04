<?php

/**
 * Class Aoe_TemplateImport_Block_Html
 *
 * @author Manish Jain
 * @author Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Html extends Mage_Page_Block_Html
{

    protected $config;

    /**
     * Render the page html
     * Renders all placeholders inside the page.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->helper('aoe_templateimport')->isEnabled()) {
            return parent::_toHtml();
        }

        $config = $this->getConfig();
        if (!$config) {
            Mage::log('[Aoe_TemplateImport] No configuration found for: ' . $this->getFullActionName(), Zend_Log::ERR);
            if (Mage::getIsDeveloperMode()) {
                return '[no config found for "' . $this->getFullActionName() . '"]';
            } else {
                return '';
            }
        }

        $source = $this->getSource($config);

        // insert blocks
        $page = $this;
        $source = preg_replace_callback('/<!--\s*###(.+)###\s*-->(.*)<!--\s*###\/\1###\s*-->/s', function ($matches) use ($page) {
            return $page->getChildHtml($matches[1]);
        }, $source);

        return $source;
    }

    /**
     * Get source
     *
     * @param array $config
     * @return string
     */
    public function getSource(array $config)
    {
        $cache = Mage::app()->getCacheInstance();
        $cacheKey = Mage::app()->getStore()->getCode() . '_' . md5(implode(';', $config));
        $lifetime = intval($config['lifetime']);

        if ($lifetime == 0 || !$source = $cache->load($cacheKey)) {

            $filePath = $config['path'];
            $context = null;

            // check if this is a url
            if (preg_match('%^https?://%i', $filePath)) {
                $helper = $this->helper('aoe_templateimport'); /* @var $helper Aoe_TemplateImport_Helper_Data */
                $username = $helper->getHttpUsername();
                $password = $helper->getHttpPassword();
                if (!empty($username) && !empty($password)) {
                    $context = stream_context_create(array('http' => array('header' => "Authorization: Basic " . base64_encode("$username:$password"))));
                }
            }

            $source = @file_get_contents($filePath, false, $context);

            if (trim($source) === '') {
                Mage::log('[Aoe_TemplateImport] Empty source for: ' . $filePath, Zend_Log::ERR);
                if (Mage::getIsDeveloperMode()) {
                    return '[Source for "' . $config['path'] . '" is empty]';
                } else {
                    return '';
                }
            } else {
                Mage::log('[Aoe_TemplateImport] Fetched source for: ' . $filePath . ' (length: ' . strlen($source). ')', Zend_Log::INFO);
            }

            if (!empty($config['basepath'])) {
                $source = $this->convertRelativePaths($source, $config['basepath']);
            }

            if ($lifetime > 0) {
                $cache->save($source, $cacheKey, array('aoe_templateimport'), $lifetime);
            }
        }
        return $source;
    }

    /**
     * Convert relative paths
     *
     * @param $source
     * @param $basepath
     * @return mixed
     */
    public function convertRelativePaths($source, $basepath) {
        if (!empty($basepath)) {
            $basepath = rtrim($basepath, '/');
            $source = preg_replace_callback('/<(script|img|link)(.*)(src|href)=("|\')(.+)("|\')/', function ($matches) use ($basepath) {
                $url = $matches[5];
                if (!preg_match('%^(https?:)?//%', $url)) {
                    $url = ltrim($url, '/');
                    $url = $basepath . '/' . $url;
                }
                return '<' . $matches[1] . $matches[2] . $matches[3] . '=' . $matches[4] . $url . $matches[6];
            }, $source);
        }
        return $source;
    }

    /**
     * Find the first configuration where the current full action name matches the pattern
     *
     * @return array
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            $this->config = false;
            $fullActionName = $this->getFullActionName();
            $templateConfig = $this->helper('aoe_templateimport')->getTemplateConfig();
            foreach ($templateConfig as $pattern => $config) {
                if (preg_match('/' . $pattern . '/', $fullActionName)) {
                    $this->config = $config;
                    break;
                }
            }
        }
        return $this->config;
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