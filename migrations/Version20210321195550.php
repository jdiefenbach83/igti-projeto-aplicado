<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321195550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create position table';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, operation_id INT NULL, asset_id INT NOT NULL, sequence INT NOT NULL, type ENUM(\'BUY\', \'SELL\'), date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', quantity INT NOT NULL, unit_cost NUMERIC(14, 4) NOT NULL, total_operation NUMERIC(14, 4) NOT NULL, accumulated_quantity INT NOT NULL, accumulated_total NUMERIC(14, 4) NOT NULL, average_price NUMERIC(14, 4) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_POSITION_OPERATION_ID FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE ON UPDATE CASCADE;');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_POSITION_ASSET_ID FOREIGN KEY (asset_id) REFERENCES asset (id)');
        $this->addSql('ALTER TABLE position ADD INDEX IDX_POSITION_DATE (date)');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE position');
    }
}
