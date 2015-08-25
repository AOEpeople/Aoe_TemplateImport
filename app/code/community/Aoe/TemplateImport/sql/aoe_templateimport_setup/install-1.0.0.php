<?php

/**
 * TemplateImport module install script
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('aoe_templateimport/origin'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Origin ID'
    )
    ->addColumn(
        'full_action_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Full Action Name Pattern'
    )
    ->addColumn(
        'source_url',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Source URL'
    )
    ->addColumn(
        'base_url',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Base URL'
    )
    ->addColumn(
        'lifetime',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Lifetime'
    )
    ->addColumn(
        'priority',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Priority'
    )
    ->addColumn(
        'source',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Source Content'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Origin Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Origin Creation Time'
    ) 
    ->setComment('Origin Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('aoe_templateimport/origin_store'))
    ->addColumn(
        'origin_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Origin ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'aoe_templateimport/origin_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'aoe_templateimport/origin_store',
            'origin_id',
            'aoe_templateimport/origin',
            'entity_id'
        ),
        'origin_id',
        $this->getTable('aoe_templateimport/origin'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'aoe_templateimport/origin_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Origins To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
