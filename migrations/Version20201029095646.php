<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201029095646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Product Foreign Key to Lead Entity';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->addColumn('product_id', 'bigint', ['notnull' => false]);
        $table->addIndex(['product_id'], 'idx_leads_product_id');
        $table->addForeignKeyConstraint('products', ['product_id'], ['id'], [], 'fk_leads_product_id');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(!$schema->hasTable('leads'), 'Table `leads` does not exist');
        $table = $schema->getTable('leads');
        $table->dropIndex('idx_leads_product_id');
        $table->removeForeignKey('fk_leads_product_id');
        $table->dropColumn('product_id');
    }
}
