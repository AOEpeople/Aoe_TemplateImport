<?php

/**
 * Origin resource model
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Model_Resource_Origin extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Fabrizio Branca
     */
    public function _construct()
    {
        $this->_init('aoe_templateimport/origin', 'entity_id');
    }

}