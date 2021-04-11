<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210411232809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create the pre-consolidation table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $ddl = <<<SQL
CREATE TABLE pre_consolidation (
    id INT AUTO_INCREMENT NOT NULL, 
    asset_id INT NOT NULL, 
    year SMALLINT NOT NULL, 
    month SMALLINT NOT NULL, 
    type ENUM('BUY', 'SELL'), 
    quantity INT NOT NULL, 
    total_operation NUMERIC(14, 4) NOT NULL, 
    total_costs NUMERIC(14, 4) NOT NULL, 
    created_at DATETIME NOT NULL, 
    updated_at DATETIME NOT NULL,  
    INDEX IDX_PRE_CONSOLIDATION_ASSET_ID (asset_id),
     PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL;

        $this->addSql($ddl);
        $this->addSql('ALTER TABLE pre_consolidation ADD CONSTRAINT FK_PRE_CONSOLIDATION_ASSET_ID FOREIGN KEY (asset_id) REFERENCES asset (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('DROP TABLE pre_consolidation');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
