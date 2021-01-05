<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207135815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add settings to account';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('accounts'), 'Table `accounts` does not exist');
        $table = $schema->getTable('accounts');
        $table->addColumn('settings', 'json', ['notnull' => false, 'comment' => "(DC2Type:json_document)"]);
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('accounts'), 'Table `accounts` does not exist');
        $table = $schema->getTable('accounts');
        $table->dropColumn('settings');
    }
}
