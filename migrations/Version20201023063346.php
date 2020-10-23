<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023063346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add OwnerId ForeignKey to Accounts';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('accounts'), 'Table `accounts` does not exist');
        $table = $schema->getTable('accounts');
        $table->addForeignKeyConstraint('users', ['owner_id'], ['id'], [], 'fk_accounts_owner_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('accounts'), 'Table `accounts` does not exist');
        $table = $schema->getTable('accounts');
        $table->removeForeignKey('fk_accounts_owner_id');
    }
}
