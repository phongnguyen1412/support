<?php

namespace Phong\Support\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('support_topic')) {
            $table = $installer->getConnection()->newTable($installer->getTable('support_topic'))
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'Topic ID'
                )->addColumn(
                    'status',
                    Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'Status'
                )->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'Topic Name'
                )->addColumn(
                    'url_key',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Topic URL Key'
                )->addColumn(
                    'topic_content',
                    Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Topic Post Content'
                )->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['default => 0'],
                    'Status'
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )->setComment('Topic Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('support_topic'),
                $setup->getIdxName(
                    $installer->getTable('support_topic'),
                    ['name', 'url_key', 'topic_content'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name', 'url_key', 'topic_content'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
