<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210418164030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Alter pre consolidation table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE pre_consolidation ADD negotiation_type ENUM(\'NORMAL\', \'DAYTRADE\')');
        $this->addSql('ALTER TABLE pre_consolidation ADD result NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD negative_result_last_month NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD calculation_basis NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD loss_to_compensate NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD withholding_tax NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD tax_rate NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD tax_due NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation DROP type');
        $this->addSql('ALTER TABLE pre_consolidation DROP quantity');
        $this->addSql('ALTER TABLE pre_consolidation DROP total_operation');
        $this->addSql('ALTER TABLE pre_consolidation DROP total_costs');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE pre_consolidation ADD type ENUM(\'BUY\', \'SELL\')');
        $this->addSql('ALTER TABLE pre_consolidation ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD total_operation NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation ADD total_costs NUMERIC(14, 4) NOT NULL');
        $this->addSql('ALTER TABLE pre_consolidation DROP negotiation_type');
        $this->addSql('ALTER TABLE pre_consolidation DROP result');
        $this->addSql('ALTER TABLE pre_consolidation DROP negative_result_last_month');
        $this->addSql('ALTER TABLE pre_consolidation DROP calculation_basis');
        $this->addSql('ALTER TABLE pre_consolidation DROP loss_to_compensate');
        $this->addSql('ALTER TABLE pre_consolidation DROP withholding_tax');
        $this->addSql('ALTER TABLE pre_consolidation DROP tax_rate');
        $this->addSql('ALTER TABLE pre_consolidation DROP tax_due');
    }
}
