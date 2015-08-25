<?php
/**
 * Aoe_TemplateImport extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Aoe
 * @package        Aoe_TemplateImport
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Template admin edit form
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Block_Adminhtml_Template_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'aoe_templateimport';
        $this->_controller = 'adminhtml_template';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('aoe_templateimport')->__('Save Template')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('aoe_templateimport')->__('Delete Template')
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
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_template') && Mage::registry('current_template')->getId()) {
            return Mage::helper('aoe_templateimport')->__(
                "Edit Template '%s'",
                $this->escapeHtml(Mage::registry('current_template')->getFullActionName())
            );
        } else {
            return Mage::helper('aoe_templateimport')->__('Add Template');
        }
    }
}
