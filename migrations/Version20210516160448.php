<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210516160448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create the consolidation table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $ddl = <<<SQL
CREATE TABLE consolidation (
    id INT AUTO_INCREMENT NOT NULL, 
    year SMALLINT NOT NULL, 
    month SMALLINT NOT NULL, 
    negotiation_type ENUM('NORMAL', 'DAYTRADE'), 
    market_type ENUM('SPOT', 'FUTURE'), 
    result NUMERIC(14, 4) NOT NULL, 
    accumulated_loss NUMERIC(14, 4) NOT NULL,
    compesated_loss NUMERIC(14, 4) NOT NULL, 
    basis_to_ir NUMERIC(14, 4) NOT NULL, 
    aliquot NUMERIC(14, 4) NOT NULL, 
    irrf NUMERIC(14, 4) NOT NULL, 
    ir_to_pay NUMERIC(14, 4) NOT NULL,
    created_at DATETIME NOT NULL, 
    updated_at DATETIME NOT NULL, 
    INDEX UK_CONSOLIDATION (year,month,negotiation_type,market_type), 
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL;

        $this->addSql($ddl);
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('DROP TABLE consolidation');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
