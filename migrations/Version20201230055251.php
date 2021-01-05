<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230055251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add FacebookLeadUpdate Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('facebook_lead_updates'), 'Table `facebook_lead_updates` already exists');
        $table = $schema->createTable('facebook_lead_updates');
        $table->addColumn('id', 'uuid');
        $table->setPrimaryKey(['id']);
        $table->addColumn('update_id', 'bigint');
        $table->addColumn('update_time', 'datetime');
        $table->addColumn('leadgen_id', 'bigint');
        $table->addColumn('page_id', 'bigint');
        $table->addColumn('form_id', 'bigint');
        $table->addColumn('adgroup_id', 'bigint');
        $table->addColumn('ad_id', 'bigint');
        $table->addColumn('created_time', 'datetime');
        $table->addColumn('process_count', 'smallint', ['default' => 0]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('lead_created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('created_lead_id', 'bigint', ['notnull' => false]);
        $table->addColumn('facebook_page_id', 'bigint');
        $table->addColumn('account_id', 'bigint');
        $table->addIndex(['created_lead_id'], 'idx_facebook_lead_updates_created_lead_id');
        $table->addIndex(['facebook_page_id'], 'idx_facebook_lead_updates_facebook_page_id');
        $table->addIndex(['account_id'], 'idx_facebook_lead_updates_account_id');
        $table->addForeignKeyConstraint('leads', ['created_lead_id'], ['id'], [], 'fk_facebook_lead_updates_created_lead_id');
        $table->addForeignKeyConstraint('facebook_pages', ['facebook_page_id'], ['id'], [], 'fk_facebook_lead_updates_facebook_page_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_facebook_lead_updates_account_id');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('facebook_lead_updates'), 'Table `facebook_lead_updates` does not exist');
        $schema->dropTable('facebook_lead_updates');
    }
}
