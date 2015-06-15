<?php

class Aoe_TemplateImport_Block_Html extends Mage_Page_Block_Html
{
    /**
     * Render the page html
     * Supports different placeholders:
     * - <!-- ###head### -->
     * - <!-- ###content### -->
     * - <!-- ###footer### -->
     *
     * Renders all placeholders inside the page.
     *
     * @return string
     */
    protected function _toHtml() {
        if (!$this->helper('aoe_templateimport')->isEnabled()) {
            return parent::_toHtml();
        }
        $source = $this->getSource();
        if (empty($source)) {
            return parent::_toHtml();
        }

        $page = $this;
        $source = preg_replace_callback('/<!-- ###(\w+)### -->/', function($matches) use($page){
                return $page->getChildHtml($matches[1]);
            }, $source);

        return $source;
    }

    /**
     * @author Manish Jain <manish.jain@aoe.com>
     * @return string
     */
    public function getSource() {
        $filePath = $this->getFilePath();
        if (empty($filePath)) {
            return $filePath;
        }
        $context = null;
        if (preg_match('%^((http(s)?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', $filePath)) {
            $username = $this->helper('aoe_templateimport')->getHttpUsername();
            $password = $this->helper('aoe_templateimport')->getHttpPassword();
            $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
        }
        return @file_get_contents($filePath, false, $context);
    }

    /**
     * @author Manish Jain <manish.jain@aoe.com>
     * @return string
     */
    public function getFilePath() {
        $currentHandlers = $this->getCurrentHandler();
        $templatePaths = $this->helper('aoe_templateimport')->getTemplatePaths();
        if (count(array_filter($templatePaths))) {
            foreach ($templatePaths as $templatePath) {
                list($handler, $filePath, $cacheLifetime) = explode(';', $templatePath);
                foreach ($currentHandlers as $currentHandler) {
                    if ($handler == $currentHandler || $handler == '*') {
                        $this->setCacheLifetime($cacheLifetime);
                        return $filePath;
                    }
                }
            }
        }
        return '';
    }

    /**
     * @author Manish Jain <manish.jain@aoe.com>
     * @return string
     */
    public function getCurrentHandler() {
        /**
         * get Router name
         */
        $route = $this->getRequest()->getRouteName();
        /**
         * get Controller name
         */
        $controller = $this->getRequest()->getControllerName();
        /**
         * get Action name, i.e. the function inside the controller
         */
        $action = $this->getRequest()->getActionName();

        $currentHandlers = array();
        $currentHandlers[] = $route.'_'.$controller.'_'.$action;
        $currentHandlers[] = $route.'_'.$controller.'_*';

        return $currentHandlers;
    }


}
