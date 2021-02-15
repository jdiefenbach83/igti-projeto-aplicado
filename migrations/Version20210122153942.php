<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122153942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Created_at and Updated_at timestamp columns';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE broker ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE broker ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE brokerage_note ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE brokerage_note ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE brokerage_note CHANGE date date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE broker DROP created_at');
        $this->addSql('ALTER TABLE broker DROP updated_at');
        $this->addSql('ALTER TABLE brokerage_note DROP created_at');
        $this->addSql('ALTER TABLE brokerage_note DROP updated_at');
    }
}
