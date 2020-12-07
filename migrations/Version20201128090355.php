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
        return 'Add LastLeadInteraction to Lead Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->addColumn('last_interaction_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['last_interaction_id'], 'idx_leads_last_interaction_id');
        $table->addForeignKeyConstraint('lead_interactions', ['last_interaction_id'], ['id'], [], 'fk_leads_last_interaction_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->removeForeignKey('fk_leads_last_interaction_id');
        $table->dropColumn('last_interaction_id');
    }
}
