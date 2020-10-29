<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201029131034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add LeadSource Foreign Key to Lead Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->addColumn('lead_source_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['lead_source_id'], 'idx_leads_lead_source_id');
        $table->addForeignKeyConstraint('lead_sources', ['lead_source_id'], ['id'], [], 'fk_leads_lead_source_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->dropIndex('idx_leads_lead_source_id');
        $table->removeForeignKey('fk_leads_lead_source_id');
        $table->dropColumn('lead_source_id');
    }
}
