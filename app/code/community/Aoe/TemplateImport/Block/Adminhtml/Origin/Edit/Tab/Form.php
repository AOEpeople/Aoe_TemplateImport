<?php

/**
 * Origin edit form tab
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
        $form->setHtmlIdPrefix('origin_');
        $form->setFieldNameSuffix('origin');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'origin_form',
            array('legend' => Mage::helper('aoe_templateimport')->__('Origin'))
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
        $field = $fieldset->addField(
            'store_id',
            'select',
            array(
                'name'     => 'store_id',
                'label'    => Mage::helper('aoe_templateimport')->__('Store View'),
                'title'    => Mage::helper('aoe_templateimport')->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false),
            )
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
            'priority',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Priority'),
                'name'  => 'priority',
                'required'  => true,
                'class' => 'required-entry',
            )
        );

        $fieldset = $form->addFieldset(
            'origin_form_source',
            array('legend' => Mage::helper('aoe_templateimport')->__('Source'))
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
            'http_username',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('HTTP Username'),
                'name'  => 'http_username',
            )
        );

        $fieldset->addField(
            'http_password',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('HTTP Password'),
                'name'  => 'http_password',
            )
        );

        $fieldset->addField(
            'base_url',
            'text',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Base URL'),
                'name'  => 'base_url',
                'after_element_html' => '<small>' .Mage::helper('aoe_templateimport')->__('This URL will be prepended to all relative paths (e.g. js/css) in the HTML source.') . '</small>'
           )
        );

        $fieldset->addField(
            'assertions',
            'textarea',
            array(
                'label' => Mage::helper('aoe_templateimport')->__('Assertions'),
                'name'  => 'assertions',
                'after_element_html' => '<small>' .Mage::helper('aoe_templateimport')->__('Regex or "marker:markerName" (e.g. marker:content)') . '</small>'
            )
        );

        $formValues = Mage::registry('current_origin')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getOriginData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOriginData());
            Mage::getSingleton('adminhtml/session')->setOriginData(null);
        } elseif (Mage::registry('current_origin')) {
            $formValues = array_merge($formValues, Mage::registry('current_origin')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
