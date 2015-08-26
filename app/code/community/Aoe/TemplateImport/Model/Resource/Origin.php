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

    public function loadByFullActionNameAndStore(Aoe_TemplateImport_Model_Origin $object, $fullActionName, $storeId)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
            ->from($this->getMainTable())
            ->where('status = 1')
            ->where('store_id = ?', $storeId)
            ->where('? like full_action_name', $fullActionName)
            ->order('priority asc')
            ->limit(1);

        $data = $read->fetchRow($select);

        if (!$data) {
            return false;
        }

        $object->setData($data);

        $this->_afterLoad($object);
        return true;
    }

}