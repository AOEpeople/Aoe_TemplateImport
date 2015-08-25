<?php

/**
 * Class Aoe_TemplateImport_Model_Resource_Config_Collection
 *
 * @author Fabrizio Branca
 * @since 2015-08-25
 */
class Aoe_TemplateImport_Model_Resource_Config_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize resource collection
     */
    public function _construct()
    {
        $this->_init('aoe_templateimport/config');
    }

}
