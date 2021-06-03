<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210601014757 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE consolidation ADD sales_total DECIMAL(14, 4) DEFAULT .0 AFTER result');
        $this->addSql('ALTER TABLE consolidation ADD is_exempt TINYINT(1) DEFAULT 0 AFTER sales_total');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE consolidation DROP sales_total');
        $this->addSql('ALTER TABLE consolidation DROP is_exempt');
    }

    private function validateDatabase(): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
