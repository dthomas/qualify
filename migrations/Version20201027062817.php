<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201027062817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('leads'), 'Table `leads` already exists');
        $table = $schema->createTable('leads');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', 'string', ['length' => 120]);
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
        $table->addColumn('is_qualified', 'boolean', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('created_by_id', 'bigint');
        $table->addColumn('updated_by_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['account_id'], 'idx_leads_account_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_leads_account_id');
        $table->addForeignKeyConstraint('users', ['created_by_id'], ['id'], [], 'fk_leads_created_by_id');
        $table->addForeignKeyConstraint('users', ['updated_by_id'], ['id'], [], 'fk_leads_updated_by_id');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->dropIndex('idx_leads_account_id');
        $table->removeForeignKey('fk_leads_account_id');
        $table->removeForeignKey('fk_leads_created_by_id');
        $table->removeForeignKey('fk_leads_updated_by_id');
        $schema->dropTable('leads');
    }
}
