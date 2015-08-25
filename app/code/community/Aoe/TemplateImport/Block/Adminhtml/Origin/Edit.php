<?php

/**
 * Origin admin edit form
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Fabrizio Branca
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'aoe_templateimport';
        $this->_controller = 'adminhtml_origin';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('aoe_templateimport')->__('Save Origin')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('aoe_templateimport')->__('Delete Origin')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('aoe_templateimport')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Fabrizio Branca
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_origin') && Mage::registry('current_origin')->getId()) {
            return Mage::helper('aoe_templateimport')->__(
                "Edit Origin '%s'",
                $this->escapeHtml(Mage::registry('current_origin')->getFullActionName())
            );
        } else {
            return Mage::helper('aoe_templateimport')->__('Add Origin');
        }
    }
}
