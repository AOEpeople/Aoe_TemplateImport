<?php

/**
 * TemplateImport module install script
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Fabrizio Branca
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
        Varien_Db_Ddl_Table::TYPE_VARBINARY, Varien_Db_Ddl_Table::MAX_VARBINARY_SIZE,
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
    ->addColumn(
        'http_username',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'HTTP Username'
    )
    ->addColumn(
        'http_password',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'HTTP Password'
    )
    ->setComment('Origin Table');
$this->getConnection()->createTable($table);
$this->endSetup();
