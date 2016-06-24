<?php

/**
 * Origin admin grid block
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
 */
class Aoe_TemplateImport_Block_Adminhtml_Origin_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Fabrizio Branca
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('originGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Grid
     * @author Fabrizio Branca
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('aoe_templateimport/origin')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Grid
     * @author Fabrizio Branca
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
        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                array(
                    'header' => Mage::helper('aoe_templateimport')->__('Store Views'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback'
                    => array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('aoe_templateimport')->__('Updated'),
                'index'  => 'updated_at',
                'type'=> 'datetime',
            )
        );
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
//        $this->addExportType('*/*/exportCsv', Mage::helper('aoe_templateimport')->__('CSV'));
//        $this->addExportType('*/*/exportExcel', Mage::helper('aoe_templateimport')->__('Excel'));
//        $this->addExportType('*/*/exportXml', Mage::helper('aoe_templateimport')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Grid
     * @author Fabrizio Branca
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('origin');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('aoe_templateimport')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('aoe_templateimport')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'refresh',
            array(
                'label'=> Mage::helper('aoe_templateimport')->__('Refresh'),
                'url'  => $this->getUrl('*/*/massRefresh')
            )
        );
        $this->getMassactionBlock()->addItem(
            'clone',
            array(
                'label'=> Mage::helper('aoe_templateimport')->__('Clone'),
                'url'  => $this->getUrl('*/*/massClone')
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
                    ),
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'store',
            array(
                'label'      => Mage::helper('aoe_templateimport')->__('Change store'),
                'url'        => $this->getUrl('*/*/massStore', array('_current'=>true)),
                'additional' => array(
                    'store_id' => array(
                        'name'   => 'store_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('aoe_templateimport')->__('Store'),
                        'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false)
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
     * @param Aoe_TemplateImport_Model_Origin
     * @return string
     * @author Fabrizio Branca
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
     * @author Fabrizio Branca
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Aoe_TemplateImport_Block_Adminhtml_Origin_Grid
     * @author Fabrizio Branca
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

}
