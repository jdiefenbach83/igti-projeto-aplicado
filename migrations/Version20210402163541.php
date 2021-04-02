<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402163541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add total_fees and total_costs columns to Operation Table';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation ADD total_fees NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE operation ADD total_costs NUMERIC(14, 4) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP total_fees');
        $this->addSql('ALTER TABLE operation DROP total_costs');
    }
}
