<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023061858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($schema->hasTable('users'), 'Table `users` already exists');
        $table = $schema->createTable('users');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', 'string', ['length' => 120]);
        $table->addColumn('email', 'string', ['length' => 180]);
        $table->addColumn('phone', 'string', ['length' => 15, 'notnull' => false]);
        $table->addColumn('roles', 'json');
        $table->addColumn('password', 'string');
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('account_id', 'bigint');
        $table->addForeignKeyConstraint('accounts', ['account_id'], ['id'], [], 'fk_users_account_id');
        $table->addUniqueIndex(['email']);
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('users'), 'Table `users` does not exist');
        $table = $schema->getTable('users');
        $table->removeForeignKey('fk_users_account_id');
        $schema->dropTable('users');
    }
}
