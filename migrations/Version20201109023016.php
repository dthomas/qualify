<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201109023016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Opportunity Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($schema->hasTable('opportunities'), 'Table `opportunities` already exists');
        $table = $schema->createTable('opportunities');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', 'string', ['length' => 180]);
        $table->addColumn('contact_phone', 'string', ['length' => 15, 'notnull' => false]);
        $table->addColumn('contact_is_phone_verified', 'boolean', ['notnull' => false]);
        $table->addColumn('contact_email', 'string', ['length' => 80, 'notnull' => false]);
        $table->addColumn('contact_is_email_verified', 'boolean', ['notnull' => false]);
        $table->addColumn('address_street', 'string', ['length' => 180, 'notnull' => false]);
        $table->addColumn('address_landmark', 'string', ['length' => 180, 'notnull' => false]);
        $table->addColumn('address_locality', 'string', ['length' => 180, 'notnull' => false]);
        $table->addColumn('address_district', 'string', ['length' => 180, 'notnull' => false]);
        $table->addColumn('address_state', 'string', ['length' => 80, 'notnull' => false]);
        $table->addColumn('address_postal_code', 'string', ['length' => 10, 'notnull' => false]);
        $table->addColumn('address_plus_code', 'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('parent_lead_id', 'bigint');
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('created_by_id', 'bigint');
        $table->addColumn('updated_by_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['parent_lead_id'], 'idx_opportunities_parent_lead_id');
        $table->addIndex(['account_id'], 'idx_opportunities_account_id');
        $table->addIndex(['created_by_id'], 'idx_opportunities_created_by_id');
        $table->addIndex(['updated_by_id'], 'idx_opportunities_updated_by_id');
        $table->addForeignKeyConstraint('leads', ['parent_lead_id'], ['id'], [], 'fk_opportunities_parent_lead_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_opportunities_account_id');
        $table->addForeignKeyConstraint('users', ['created_by_id'], ['id'], [], 'fk_opportunities_created_by_id');
        $table->addForeignKeyConstraint('users', ['updated_by_id'], ['id'], [], 'fk_opportunities_updated_by_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('opportunities'), 'Table `opportunities` does not exist');
        $table = $schema->getTable('opportunities');
        $table->dropIndex('idx_opportunities_parent_lead_id');
        $table->dropIndex('idx_opportunities_account_id');
        $table->dropIndex('idx_opportunities_created_by_id');
        $table->dropIndex('idx_opportunities_updated_by_id');
        $table->removeForeignKey('fk_opportunities_parent_lead_id');
        $table->removeForeignKey('fk_opportunities_account_id');
        $table->removeForeignKey('fk_opportunities_created_by_id');
        $table->removeForeignKey('fk_opportunities_updated_by_id');
        $schema->dropTable('opportunities');
    }
}
