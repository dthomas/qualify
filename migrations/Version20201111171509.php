<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111171509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add OpportunityItem Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('opportunity_items'), 'Table `opportunity_items` already exists');
        $table = $schema->createTable('opportunity_items');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('title', 'string', ['length' => 180]);
        $table->addColumn('remarks', 'text', ['notnull' => false]);
        $table->addColumn('history', 'json', ['notnull' => false, 'comment' => '(DC2Type:json_document)']);
        $table->addColumn('status', 'string', ['length' => 48]);
        $table->addColumn('due_at', 'datetime', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('product_id', 'bigint');
        $table->addColumn('opportunity_id', 'bigint');
        $table->addColumn('created_by_id', 'bigint');
        $table->addColumn('updated_by_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['account_id'], 'idx_opportunity_items_account_id');
        $table->addIndex(['product_id'], 'idx_opportunity_items_product_id');
        $table->addIndex(['opportunity_id'], 'idx_opportunity_items_opportunity_id');
        $table->addIndex(['created_by_id'], 'idx_opportunity_items_created_by_id');
        $table->addIndex(['updated_by_id'], 'idx_opportunity_items_updated_by_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_opportunity_items_account_id');
        $table->addForeignKeyConstraint('products', ['product_id'], ['id'], [], 'fk_opportunity_items_product_id');
        $table->addForeignKeyConstraint('opportunities', ['opportunity_id'], ['id'], [], 'fk_opportunity_items_opportunity_id');
        $table->addForeignKeyConstraint('users', ['created_by_id'], ['id'], [], 'fk_opportunity_items_created_by_id');
        $table->addForeignKeyConstraint('users', ['updated_by_id'], ['id'], [], 'fk_opportunity_items_updated_by_id');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('opportunity_items'), 'Table `opportunity_items` does not exist');
        $schema->dropTable('opportunity_items');
    }
}
