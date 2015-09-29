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
 * @method getAssertions()
 *
 * @method setFullActionName($param)
 * @method setSourceUrl($param)
 * @method setBaseUrl($param)
 * @method setPriority($param)
 * @method setSource($param)
 * @method setStatus($param)
 * @method setUpdatedAt($param)
 * @method setCreatedAt($param)
 * @method setStoreId($param)
 * @method setHttpUsername($param)
 * @method setHttpPassword($param)
 * @method setAssertions($param)
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
     * Last error message
     *
     * @var string
     */
    protected $lastError;

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
     *
     * @return bool
     */
    public function refresh()
    {
        try {
            $sourceHelper = Mage::helper('aoe_templateimport/source'); /* @var $sourceHelper Aoe_TemplateImport_Helper_Source */
            $dataHelper = Mage::helper('aoe_templateimport'); /* @var $dataHelper Aoe_TemplateImport_Helper_Data */

            $source = $sourceHelper->fetchSource(
                $dataHelper->filter($this->getSourceUrl()),
                $this->getHttpUsername(),
                $this->getHttpPassword()
            );

            $this->checkAssertions($source);

            if (empty($source)) {
                Mage::throwException('Empty source');
            }

            $source = $sourceHelper->convertRelativePaths(
                $source,
                $dataHelper->filter($this->getBaseUrl())
            );

            $this->setSource($source);
            $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
            $this->save();
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            Mage::log('[Aoe_TemplateImport] Error retrieving source for origin: ' . $this->getId() . ' Message: ' . $e->getMessage(),  Zend_Log::ERR);
            return false;
        }
        return true;
    }

    /**
     * Get last error message
     *
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Check assertions
     *
     * @param string $source
     * @throws Exception
     */
    public function checkAssertions($source)
    {
        $dataHelper = Mage::helper('aoe_templateimport'); /* @var $dataHelper Aoe_TemplateImport_Helper_Data */
        $sourceHelper = Mage::helper('aoe_templateimport/source'); /* @var $sourceHelper Aoe_TemplateImport_Helper_Source */
        foreach ($dataHelper->trimExplode("\n", $this->getAssertions(), true) as $assertion) {
            if (strpos($assertion, 'marker:') === 0) {
                $marker = trim(substr($assertion, 7));
                if (!$sourceHelper->markerExists($source, $marker)) {
                    Mage::throwException('Could not find marker "'.$marker.'"');
                }
            } else {
                if (!preg_match($assertion, $source)) {
                    Mage::throwException('Pattern "'.$assertion.'" not found');
                }
            }
        }
    }

    /**
     * Create clone
     *
     * @return Aoe_TemplateImport_Model_Origin
     */
    public function createClone()
    {
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
