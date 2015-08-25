<?php

/**
 * Origin admin block
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_origin';
        $this->_blockGroup         = 'aoe_templateimport';
        parent::__construct();
        $this->_headerText         = Mage::helper('aoe_templateimport')->__('Origin');
        $this->_updateButton('add', 'label', Mage::helper('aoe_templateimport')->__('Add Origin'));

    }
}
