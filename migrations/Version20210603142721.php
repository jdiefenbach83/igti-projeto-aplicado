<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210603142721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user table';
    }

    public function up(Schema $schema): void
    {
        $ddl = <<<SQL
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL, 
    email VARCHAR(180) NOT NULL, 
    roles JSON NOT NULL, 
    password VARCHAR(255) NOT NULL, 
    created_at DATETIME NOT NULL, 
    updated_at DATETIME NOT NULL, 
    UNIQUE INDEX UK_USER_EMAIL (email), 
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL;
        $this->validateDatabase();

        $this->addSql($ddl);
    }

    public function down(Schema $schema): void
    {
        $this->validateDatabase();

        $this->addSql('DROP TABLE user');
    }

    private function validateDatabase(): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
