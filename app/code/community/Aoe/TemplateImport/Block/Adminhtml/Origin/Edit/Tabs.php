<?php

/**
 * Origin admin edit tabs
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Fabrizio Branca
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('origin_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('aoe_templateimport')->__('Origin'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Tabs
     * @author Fabrizio Branca
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_origin',
            array(
                'label'   => Mage::helper('aoe_templateimport')->__('Origin'),
                'title'   => Mage::helper('aoe_templateimport')->__('Origin'),
                'content' => $this->getLayout()->createBlock(
                    'aoe_templateimport/adminhtml_origin_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve origin entity
     *
     * @access public
     * @return Aoe_TemplateImport_Model_Origin
     * @author Fabrizio Branca
     */
    public function getOrigin()
    {
        return Mage::registry('current_origin');
    }
}
