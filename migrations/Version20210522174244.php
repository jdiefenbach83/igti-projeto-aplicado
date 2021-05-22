<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210522174244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Goods table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $ddl = <<<SQL
CREATE TABLE good (
    id INT AUTO_INCREMENT NOT NULL,
    asset_id INT NOT NULL,
    year SMALLINT NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    situation_year_before NUMERIC(14, 4) NOT NULL,
    situation_current_year NUMERIC(14, 4) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT FK_GOOD_ASSET_ID FOREIGN KEY (asset_id) REFERENCES asset (id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL;
        $this->addSql($ddl);
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('DROP TABLE good');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
