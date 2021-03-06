<?php

/**
 * Class Aoe_TemplateImport_Model_Cron
 *
 * @author Fabrizio Branca
 * @since 2015-08-26
 */
class Aoe_TemplateImport_Model_Cron {


    /**
     * Refresh all origins
     *
     * @return string
     */
    public function refreshAll()
    {
        $collection = Mage::getModel('aoe_templateimport/origin')
            ->getCollection()
            ->addFieldToFilter('status', '1');
        $stats = array();
        $success = true;
        foreach ($collection as $origin) { /* @var $origin Aoe_TemplateImport_Model_Origin */
            $start = time();
            $res = $origin->refresh();
            $stats[$origin->getId()] = array(
                'origin' => $origin->getSourceUrl(),
                'store' => $origin->getStoreId(),
                'duration' => time() - $start,
                'status' => $res ? 'success' : 'failed'
            );
            if (!$res) {
                $success = false;
                $stats[$origin->getId()]['error'] = $origin->getLastError();
            }
        }
        return $success ? $stats : 'ERROR: Updating one or more origins failed: ' . var_export($stats, true);
    }

}