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
 * Template edit form tab
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Block_Adminhtml_Template_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('template_');
        $form->setFieldNameSuffix('template');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'template_form',
            array('legend' => Mage::helper('aoe_templateimport')->__('Template'))
        );

        $fieldset->addField(
            'full_action_name',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Full Action Name Pattern'),
                'name'  => 'full_action_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'source_url',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Source URL'),
                'name'  => 'source_url',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'base_url',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Base URL'),
                'name'  => 'base_url',

           )
        );

        $fieldset->addField(
            'lifetime',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Lifetime'),
                'name'  => 'lifetime',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'priority',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Priority'),
                'name'  => 'priority',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'source',
            'textarea',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Source Content'),
                'name'  => 'source',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('aoe_templateimport')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('aoe_templateimport')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('aoe_templateimport')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_template')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_template')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getTemplateData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getTemplateData());
            Mage::getSingleton('adminhtml/session')->setTemplateData(null);
        } elseif (Mage::registry('current_template')) {
            $formValues = array_merge($formValues, Mage::registry('current_template')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
