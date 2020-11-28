<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201128090355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add LeadStage to Lead Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->addColumn('lead_stage_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['lead_stage_id'], 'idx_leads_lead_stage_id');
        $table->addForeignKeyConstraint('lead_stages', ['lead_stage_id'], ['id'], [], 'fk_leads_lead_stage_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->removeForeignKey('fk_leads_lead_stage_id');
        $table->dropColumn('lead_stage_id');
    }
}
