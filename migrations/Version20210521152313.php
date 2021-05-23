<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210521152313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add accumulated and compesated irrf columns';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE consolidation ADD accumulated_irrf NUMERIC(14, 4) NOT NULL AFTER irrf');
        $this->addSql('ALTER TABLE consolidation ADD compesated_irrf NUMERIC(14, 4) NOT NULL AFTER accumulated_irrf');
        $this->addSql('ALTER TABLE consolidation ADD irrf_to_pay NUMERIC(14, 4) NOT NULL AFTER compesated_irrf');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE consolidation DROP accumulated_irrf');
        $this->addSql('ALTER TABLE consolidation DROP compesated_irrf');
        $this->addSql('ALTER TABLE consolidation DROP irrf_to_pay');
    }

    protected function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
