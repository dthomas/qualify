<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201028172334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add LeadSource Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($schema->hasTable('lead_sources'), 'Table `lead_sources` already exists');
        $table = $schema->createTable('lead_sources');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('code', 'string', ['length' => 32]);
        $table->addColumn('name', 'string', ['length' => 180]);
        $table->addColumn('is_active', 'boolean');
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addIndex(['account_id'], 'idx_lead_sources_account_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_lead_sources_account_id');
        $table->addIndex(['is_active'], 'idx_lead_sources_is_active');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('lead_sources'), 'Table `lead_sources` does not exist');
        $table = $schema->getTable('lead_sources');
        $table->dropIndex('idx_lead_sources_account_id');
        $table->dropIndex('idx_lead_sources_is_active');
        $table->removeForeignKey('fk_lead_sources_account_id');
        $schema->dropTable('lead_sources');
    }
}
