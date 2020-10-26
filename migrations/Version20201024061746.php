<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201024061746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Product Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($schema->hasTable('products'), 'Table `products` already exists');
        $table = $schema->createTable('products');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('code', 'string', ['length' => 32]);
        $table->addColumn('name', 'string', ['length' => 180]);
        $table->addColumn('is_available', 'boolean', ['default' => true, 'notnull' => false]);
        $table->addColumn('is_discontinued', 'boolean', ['default' => false, 'notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_products_account_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('products'), 'Table `products` does not exist');
        $table = $schema->getTable('products');
        $table->removeForeignKey('fk_products_account_id');
        $schema->dropTable('products');
    }
}
