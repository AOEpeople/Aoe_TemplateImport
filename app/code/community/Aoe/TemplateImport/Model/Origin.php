<?php

/**
 * Origin model
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Model_Origin extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'aoe_templateimport_origin';
    const CACHE_TAG = 'aoe_templateimport_origin';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'aoe_templateimport_origin';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'origin';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Fabrizio Branca
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('aoe_templateimport/origin');
    }

    /**
     * before save origin
     *
     * @access protected
     * @return Aoe_TemplateImport_Model_Origin
     * @author Fabrizio Branca
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save origin relation
     *
     * @access public
     * @return Aoe_TemplateImport_Model_Origin
     * @author Fabrizio Branca
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Fabrizio Branca
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}