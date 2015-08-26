<?php

/**
 * Origin model
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 *
 *
 * @method getFullActionName()
 * @method getSourceUrl()
 * @method getBaseUrl()
 * @method getPriority()
 * @method getSource()
 * @method getStatus()
 * @method getUpdatedAt()
 * @method getCreatedAt()
 * @method getStoreId()
 * @method getHttpUsername()
 * @method getHttpPassword()
 *
 * @method setFullActionName()
 * @method setSourceUrl()
 * @method setBaseUrl()
 * @method setPriority()
 * @method setSource()
 * @method setStatus()
 * @method setUpdatedAt()
 * @method setCreatedAt()
 * @method setStoreId()
 * @method setHttpUsername()
 * @method setHttpPassword()
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
     * Load by full action name
     *
     * @param $fullActionName
     * @param $storeId
     */
    public function loadByFullActionNameAndStore($fullActionName, $storeId)
    {
        $this->_getResource()->loadByFullActionNameAndStore($this, $fullActionName, $storeId);
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

    /**
     * Fetch origin
     */
    public function refresh()
    {
        $sourceHelper = Mage::helper('aoe_templateimport/source'); /* @var $sourceHelper Aoe_TemplateImport_Helper_Source */

        $source = $sourceHelper->fetchSource(
            Mage::helper('aoe_templateimport')->filter($this->getSourceUrl()),
            $this->getHttpUsername(),
            $this->getHttpPassword()
        );

        if (empty($source)) {
            return false;
        }

        $source = $sourceHelper->convertRelativePaths(
            $source,
            Mage::helper('aoe_templateimport')->filter($this->getBaseUrl())
        );

        $this->setSource($source);
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        $this->save();

        return true;
    }

    public function createClone() {
        $data = $this->getData();
        unset($data['entity_id']);
        unset($data['updated_at']);
        unset($data['source']);
        $data['full_action_name'] = 'CLONE: ' . $data['full_action_name'];
        $data['status'] = 0;
        $copy = new Aoe_TemplateImport_Model_Origin();
        $copy->setData($data);
        return $copy;
    }
}
