<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210413135926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add negotiation type and quantity balance into Position table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position ADD negotiation_type ENUM(\'NORMAL\', \'DAYTRADE\')');
        $this->addSql('ALTER TABLE position ADD quantity_balance INTEGER NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE position DROP negotiation_type');
        $this->addSql('ALTER TABLE position DROP quantity_balance');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
