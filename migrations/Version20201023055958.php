<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023055958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Account Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('accounts'), 'Table `accounts` already exists');
        $table = $schema->createTable('accounts');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', 'string', ['length' => 120]);
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('owner_id', 'bigint');
        $table->addUniqueIndex(['owner_id']);
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('accounts'), 'Table `accounts` does not exist');
        $schema->dropTable('accounts');
    }
}
