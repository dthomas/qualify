<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201030084747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add LeadInteraction Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($schema->hasTable('lead_interactions'), 'Table `lead_interactions` already exists');
        $table = $schema->createTable('lead_interactions');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('callback_at', 'datetime', ['notnull' => false]);
        $table->addColumn('remarks', 'text', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('user_id', 'bigint');
        $table->addColumn('lead_stage_id', 'bigint');
        $table->addColumn('parent_lead_id', 'bigint');
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addIndex(['account_id'], 'idx_lead_interactions_account_id');
        $table->addIndex(['user_id'], 'idx_lead_interactions_user_id');
        $table->addIndex(['lead_stage_id'], 'idx_lead_interactions_lead_stage_id');
        $table->addIndex(['parent_lead_id'], 'idx_lead_interactions_parent_lead_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_lead_interactions_account_id');
        $table->addForeignKeyConstraint('users', ['user_id'], ['id'], [], 'fk_lead_interactions_user_id');
        $table->addForeignKeyConstraint('lead_stages', ['lead_stage_id'], ['id'], [], 'fk_lead_interactions_lead_stage_id');
        $table->addForeignKeyConstraint('leads', ['parent_lead_id'], ['id'], [], 'fk_lead_interactions_parent_lead_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('lead_interactions'), 'Table `lead_interactions` does not exist');
        $table = $schema->getTable('lead_interactions');
        $table->dropIndex('idx_lead_interactions_user_id');
        $table->dropIndex('idx_lead_interactions_account_id');
        $table->dropIndex('idx_lead_interactions_lead_stage_id');
        $table->dropIndex('idx_lead_interactions_parent_lead_id');
        $table->removeForeignKey('fk_lead_interactions_user_id');
        $table->removeForeignKey('fk_lead_interactions_account_id');
        $table->removeForeignKey('fk_lead_interactions_lead_stage_id');
        $table->removeForeignKey('fk_lead_interactions_parent_lead_id');
        $schema->dropTable('lead_interactions');
    }
}
