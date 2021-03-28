<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316234846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add columns to prorate fees and costs';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation ADD operational_fee NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE operation ADD registration_fee NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE operation ADD emolument_fee NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE operation ADD brokerage NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE operation ADD iss_pis_cofins NUMERIC(14, 4) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP operational_fee');
        $this->addSql('ALTER TABLE operation DROP registration_fee');
        $this->addSql('ALTER TABLE operation DROP emolument_fee');
        $this->addSql('ALTER TABLE operation DROP brokerage');
        $this->addSql('ALTER TABLE operation DROP iss_pis_cofins');
    }
}
