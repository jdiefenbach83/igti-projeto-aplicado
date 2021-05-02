<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210410231923 extends AbstractMigration
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
    asset_type ENUM('STOCK', 'FUTURE_CONTRACT'), 
    negotiation_type ENUM('NORMAL', 'DAYTRADE'), 
    total_bought NUMERIC(14, 4) NOT NULL, 
    total_bought_costs NUMERIC(14, 4) NOT NULL, 
    total_quantity_sold INT NOT NULL,
    total_sold NUMERIC(14, 4) NOT NULL, 
    total_sold_costs NUMERIC(14, 4) NOT NULL,
    accumulated_loss NUMERIC(14, 4) NOT NULL, 
    balance NUMERIC(14, 4) NOT NULL, 
    total_costs NUMERIC(14, 4) NOT NULL, 
    compesated_loss NUMERIC(14, 4) NOT NULL, 
    irrf_charged NUMERIC(14, 4) NOT NULL, 
    irrf_calculated NUMERIC(14, 4) NOT NULL, 
    ir_to_pay NUMERIC(14, 4) NOT NULL, 
    created_at DATETIME NOT NULL, 
    updated_at DATETIME NOT NULL, 
    INDEX UK_CONSOLIDATION (year, month, asset_type, negotiation_type), 
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
