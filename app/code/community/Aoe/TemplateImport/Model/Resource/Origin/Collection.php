<?php

/**
 * Origin collection resource model
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Model_Resource_Origin_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Fabrizio Branca
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('aoe_templateimport/origin');
    }

    /**
     * get origins as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Fabrizio Branca
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='full_action_name', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Fabrizio Branca
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='full_action_name')
    {
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Fabrizio Branca
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Aoe_TemplateImport_Model_Resource_Origin_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            if (!is_array($store)) {
                $store = array($store);
            }

            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }

            $this->addFilter('store_id', array('in' => $store), 'public');
        }
        return $this;
    }
}
