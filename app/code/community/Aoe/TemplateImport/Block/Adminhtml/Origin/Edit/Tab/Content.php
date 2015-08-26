<?php

/**
 * Origin edit form tab
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Tab_Form
     * @author Fabrizio Branca
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('origin');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'origin_content',
            array('legend' => Mage::helper('aoe_templateimport')->__('Content'))
        );
        $fieldset->addField(
            'updated_at',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Updated'),
                'name'  => 'updated_at',
                'readonly' => true,
                'disabled' => true
            )
        );
        $fieldset->addField(
            'source',
            'textarea',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Source Content'),
                'name'  => 'source',
                'readonly' => true,
                'disabled' => true,
                'style' => 'width: 1000px; height: 500px'
           )
        );
        $form->addValues(Mage::registry('current_origin')->getData());
        return parent::_prepareForm();
    }
}
