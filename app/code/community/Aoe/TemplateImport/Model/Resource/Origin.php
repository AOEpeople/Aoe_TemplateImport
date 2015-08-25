<?php

/**
 * Origin resource model
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Model_Resource_Origin extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('aoe_templateimport/origin', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $originId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($originId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('aoe_templateimport/origin_store'), 'store_id')
            ->where('origin_id = ?', (int)$originId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Aoe_TemplateImport_Model_Resource_Origin
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Aoe_TemplateImport_Model_Origin $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('templateimport_origin_store' => $this->getTable('aoe_templateimport/origin_store')),
                $this->getMainTable() . '.entity_id = templateimport_origin_store.origin_id',
                array()
            )
            ->where('templateimport_origin_store.store_id IN (?)', $storeIds)
            ->order('templateimport_origin_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign origin to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Aoe_TemplateImport_Model_Resource_Origin
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('aoe_templateimport/origin_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'origin_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'origin_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }}
