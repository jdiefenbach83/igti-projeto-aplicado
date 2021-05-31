<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210531210442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Alter asset table add market type column';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE asset CHANGE type type ENUM(\'STOCK\', \'INDEX\', \'DOLAR\')');
        $this->addSql('ALTER TABLE asset ADD market_type ENUM(\'SPOT\', \'FUTURE\') AFTER type');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE asset CHANGE type type ENUM(\'STOCK\', \'FUTURE_CONTRACT\')');
        $this->addSql('ALTER TABLE asset DROP market_type');

        $this->validateDatabase();
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
