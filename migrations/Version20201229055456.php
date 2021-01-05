<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229055456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add FacebookPage Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('facebook_pages'), 'Table `facebook_pages` already exists');
        $table = $schema->createTable('facebook_pages');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('fbid', 'bigint');
        $table->addColumn('name', 'string');
        $table->addColumn('subscribed', 'boolean', ['default' => false]);
        $table->addColumn('access_token', 'text', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addColumn('lead_source_id', 'bigint', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addIndex(['account_id'], 'idx_facebook_pages_account_id');
        $table->addIndex(['lead_source_id'], 'idx_facebook_pages_lead_source_id');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_facebook_pages_account_id');
        $table->addForeignKeyConstraint('lead_sources', ['lead_source_id'], ['id'], [], 'fk_facebook_pages_lead_sources_id');
        $table->addUniqueIndex(['fbid'], 'idx_facebook_pages_fbid_uniq');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('facebook_pages'), 'Table `facebook_pages` does not exist');
        $schema->dropTable('facebook_pages');
    }
}
