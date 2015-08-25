<?php

/**
 * Origin edit form
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Edit_Form
     * @author Fabrizio Branca
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl('*/*/save',
                    array('id' => $this->getRequest()->getParam('id'))
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
