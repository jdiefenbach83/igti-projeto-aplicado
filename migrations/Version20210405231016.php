<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210405231016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename unit_cost column to unit_price and add new columns';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE position RENAME COLUMN unit_cost TO unit_price');
        $this->addSql('ALTER TABLE position ADD total_costs NUMERIC(14, 4) NOT NULL AFTER total_operation');
        $this->addSql('ALTER TABLE position ADD accumulated_costs NUMERIC(14, 4) NOT NULL AFTER accumulated_total');
        $this->addSql('ALTER TABLE position ADD average_price_to_ir NUMERIC(14, 4) NOT NULL AFTER average_price');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE position RENAME COLUMN unit_price TO unit_cost');
        $this->addSql('ALTER TABLE position DROP total_costs');
        $this->addSql('ALTER TABLE position DROP accumulated_costs');
        $this->addSql('ALTER TABLE position DROP average_price_to_ir');
    }
}
