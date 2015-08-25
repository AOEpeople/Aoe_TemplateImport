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
 * Template admin grid block
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Block_Adminhtml_Template_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('templateGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('aoe_templateimport/template')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('aoe_templateimport')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'full_action_name',
            array(
                'header'    => Mage::helper('aoe_templateimport')->__('Full Action Name Pattern'),
                'align'     => 'left',
                'index'     => 'full_action_name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('aoe_templateimport')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('aoe_templateimport')->__('Enabled'),
                    '0' => Mage::helper('aoe_templateimport')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'source_url',
            array(
                'header' => Mage::helper('aoe_templateimport')->__('Source URL'),
                'index'  => 'source_url',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'priority',
            array(
                'header' => Mage::helper('aoe_templateimport')->__('Priority'),
                'index'  => 'priority',
                'type'=> 'number',

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('aoe_templateimport')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('aoe_templateimport')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('aoe_templateimport')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('aoe_templateimport')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('aoe_templateimport')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('aoe_templateimport')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('template');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('aoe_templateimport')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('aoe_templateimport')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('aoe_templateimport')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('aoe_templateimport')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('aoe_templateimport')->__('Enabled'),
                            '0' => Mage::helper('aoe_templateimport')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Aoe_TemplateImport_Model_Template
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Aoe_TemplateImport_Model_Resource_Template_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Aoe_TemplateImport_Block_Adminhtml_Template_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
