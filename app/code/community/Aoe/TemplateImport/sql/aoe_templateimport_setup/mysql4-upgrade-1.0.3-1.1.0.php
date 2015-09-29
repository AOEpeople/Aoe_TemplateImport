<?php

$installer = $this; /* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('aoe_templateimport/origin'), 'assertions', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 65536,
    'nullable' => true,
    'default' => null,
    'comment' => 'Assertions'
));

$installer->endSetup();
