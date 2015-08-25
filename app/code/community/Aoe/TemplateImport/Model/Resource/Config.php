<?php
/**
 * Class Aoe_TemplateImport_Model_Resource_Config
 *
 * @author Fabrizio Branca
 * @since 2015-08-25
 */
class Aoe_TemplateImport_Model_Resource_Config extends Mage_Core_Model_Resource_Db_Abstract {

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('aoe_templateimport/config', 'id');
    }

}
