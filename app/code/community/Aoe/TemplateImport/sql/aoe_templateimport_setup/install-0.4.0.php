<?php

/* @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();

/**
 * Create table 'aoe_templateimport/config'
 */
$table = $this->getConnection()
    ->newTable($this->getTable('aoe_templateimport/config'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Source Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Store Id')
    ->addColumn('full_action_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Full Action Name Pattern')
    ->addColumn('source_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Source Url')
    ->addColumn('base_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Base Url')
    ->addColumn('lifetime', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Lifetime')
    ->addColumn('priority', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Piority')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Update time')
    ->addColumn('source', Varien_Db_Ddl_Table::TYPE_VARBINARY, Varien_Db_Ddl_Table::MAX_VARBINARY_SIZE, array(), 'Source');
$this->getConnection()->createTable($table);

$this->endSetup();