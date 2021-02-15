<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119002519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Brokerage Note Table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brokerage_note (id INT AUTO_INCREMENT NOT NULL, broker_id INT NOT NULL, date DATE NOT NULL, number INT NOT NULL, total_moviments NUMERIC(14, 4) NOT NULL, operational_fee NUMERIC(14, 4) NOT NULL, registration_fee NUMERIC(14, 4) NOT NULL, emolument_fee NUMERIC(14, 4) NOT NULL, iss_pis_cofins NUMERIC(14, 4) NOT NULL, total_fees NUMERIC(14, 4) NOT NULL, note_irrf_tax NUMERIC(14, 4) NOT NULL, calculated_irrf_tax NUMERIC(14, 4) NOT NULL, net_total NUMERIC(14, 4) NOT NULL, total_costs NUMERIC(14, 4) NOT NULL, result NUMERIC(14, 4) NOT NULL, calculation_basis_ir NUMERIC(14, 4) NOT NULL, calculated_ir NUMERIC(14, 4) NOT NULL, INDEX IDX_F8DB0C5B6CC064FC (broker_id), INDEX brokerage_note_date_idx (date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brokerage_note ADD CONSTRAINT FK_F8DB0C5B6CC064FC FOREIGN KEY (broker_id) REFERENCES broker (id)');
        $this->addSql('CREATE INDEX broker_code_idx ON broker (code)');
        $this->addSql('CREATE UNIQUE INDEX search_idx ON broker (name, code)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE brokerage_note');
        $this->addSql('DROP INDEX broker_code_idx ON broker');
        $this->addSql('DROP INDEX search_idx ON broker');
    }
}
