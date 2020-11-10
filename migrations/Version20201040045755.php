<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201040045755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stageType to LeadStage Entity';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('lead_stages'), 'Table `lead_stages` does not exist');
        $table = $schema->getTable('lead_stages');
        $table->addColumn('stage_type', 'string', ['length' => 16, 'default' => 'follow-up']);
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$schema->hasTable('lead_stages'), 'Table `lead_stages` does not exist');
        $table = $schema->getTable('lead_stages');
        $table->dropColumn('stage_type');
    }
}
