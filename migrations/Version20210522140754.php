<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210522140754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add IRRF Normal and Daytrade Tax columns to BrokerageNote table';
    }

    public function up(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE brokerage_note ADD irrf_normal_tax NUMERIC(14, 4) NOT NULL, ADD irrf_daytrade_tax NUMERIC(14, 4) NOT NULL, DROP note_irrf_tax, DROP calculated_irrf_tax, DROP calculation_basis_ir, DROP calculated_ir');
    }

    public function down(Schema $schema) : void
    {
        $this->validateDatabase();

        $this->addSql('ALTER TABLE brokerage_note ADD note_irrf_tax NUMERIC(14, 4) NOT NULL, ADD calculated_irrf_tax NUMERIC(14, 4) NOT NULL, ADD calculation_basis_ir NUMERIC(14, 4) NOT NULL, ADD calculated_ir NUMERIC(14, 4) NOT NULL, DROP irrf_normal_tax, DROP irrf_daytrade_tax');
    }

    private function validateDatabase(): void {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
