<?php

/**
 * Source Helper
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Helper_Source extends Mage_Core_Helper_Abstract
{

    /**
     * Fetch source
     *
     * @param $sourceUrl
     * @param null $username
     * @param null $password
     * @return string
     */
    public function fetchSource($sourceUrl, $username=null, $password=null)
    {
        // TODO: make timeout configurable
        $contextData = array('http'=> array('timeout' => 10));
        if (preg_match('%^https?://%i', $sourceUrl) && !empty($username) && !empty($password)) {
            $contextData['http']['header'] = "Authorization: Basic " . base64_encode("$username:$password");
        }
        $source = @file_get_contents($sourceUrl, false, stream_context_create($contextData));
        return trim($source);
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
     * Inject child blocks
     *
     * @param $source
     * @param Mage_Core_Block_Abstract $block
     * @return mixed
     */
    public function injectChildBlocks($source, Mage_Core_Block_Abstract $block) {
        $count = null;

        $source = preg_replace_callback('/<!--\s*###(.+)###\s*-->(.*)<!--\s*###\/\1###\s*-->/s', function ($matches) use ($block) {
            if (Mage::getIsDeveloperMode()) {
                Mage::log('[Aoe_TemplateImport] Injecting block: ' . $matches[1]);
            }
            return '<!-- BEGIN BLOCK: '.$matches[1].' -->' . $block->getChildHtml($matches[1]) . '<!-- END BLOCK: '.$matches[1].' -->';
        }, $source, -1, $count);
        if (Mage::getIsDeveloperMode()) {
            Mage::log('[Aoe_TemplateImport] Match count: ' . $count);
        }
        return $source;
    }

}
