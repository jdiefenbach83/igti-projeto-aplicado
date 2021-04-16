<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210416215803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add position price to position table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position ADD position_price NUMERIC(14, 4) NOT NULL DEFAULT 0 AFTER total_costs');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position DROP position_price');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
