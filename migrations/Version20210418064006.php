<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210418064006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add result and accumulated result at Position table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position ADD result NUMERIC(14, 4) NOT NULL DEFAULT 0 AFTER average_price_to_ir');
        $this->addSql('ALTER TABLE position ADD accumulated_result NUMERIC(14, 4) NOT NULL DEFAULT 0 AFTER result');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position DROP result');
        $this->addSql('ALTER TABLE position DROP accumulated_result');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
