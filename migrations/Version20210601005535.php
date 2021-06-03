<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210601005535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add total of sells column to pre consolidation table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE pre_consolidation ADD sales_total DECIMAL(14, 4) DEFAULT .0 AFTER result');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE pre_consolidation DROP sales_total');
    }

    private function validateDatabase(): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
