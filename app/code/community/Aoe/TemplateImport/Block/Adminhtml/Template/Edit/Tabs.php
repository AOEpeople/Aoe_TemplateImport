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
 * Template admin edit tabs
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Block_Adminhtml_Template_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('template_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('aoe_templateimport')->__('Template'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_template',
            array(
                'label'   => Mage::helper('aoe_templateimport')->__('Template'),
                'title'   => Mage::helper('aoe_templateimport')->__('Template'),
                'content' => $this->getLayout()->createBlock(
                    'aoe_templateimport/adminhtml_template_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_template',
                array(
                    'label'   => Mage::helper('aoe_templateimport')->__('Store views'),
                    'title'   => Mage::helper('aoe_templateimport')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'aoe_templateimport/adminhtml_template_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve template entity
     *
     * @access public
     * @return Aoe_TemplateImport_Model_Template
     * @author Ultimate Module Creator
     */
    public function getTemplate()
    {
        return Mage::registry('current_template');
    }
}
