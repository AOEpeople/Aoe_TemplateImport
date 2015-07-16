<?php

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

        $source = $this->getSource();

        $config = $this->getConfig();

        // preprocessing relative paths
        $basepath = $config['basepath'];
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

        // insert blocks
        $page = $this;
        $source = preg_replace_callback('/<!--\s*###(.+)###\s*-->(.*)<!--\s*###\/\1###\s*-->/s', function ($matches) use ($page) {
            return $page->getChildHtml($matches[1]);
        }, $source);

        return $source;
    }

    /**
     * @author Manish Jain <manish.jain@aoe.com>
     * @return string
     */
    public function getSource()
    {
        $config = $this->getConfig();

        $filePath = $config['path'];
        $context = null;

        // check if this is a url
        if (preg_match('%^https?://%i', $filePath)) {
            $username = $this->helper('aoe_templateimport')->getHttpUsername();
            $password = $this->helper('aoe_templateimport')->getHttpPassword();
            if (!empty($username) && !empty($password)) {
                $context = stream_context_create(array('http' => array('header' => "Authorization: Basic " . base64_encode("$username:$password"))));
            }
        }

        return @file_get_contents($filePath, false, $context);
    }

    /**
     * Find the first configuration where the current full action name matches the pattern
     *
     * @author Manish Jain <manish.jain@aoe.com>
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
     * Get full actio name
     *
     * @author Manish Jain <manish.jain@aoe.com>
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
