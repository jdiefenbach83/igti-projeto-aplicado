<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215142442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Operation Table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, brokerage_note_id INT NOT NULL, line INT NOT NULL, type ENUM(\'BUY\', \'SELL\'), asset_id INT NOT NULL, quantity INT NOT NULL, price NUMERIC(14, 4) NOT NULL, total NUMERIC(14, 4) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1981A66DBB1C40C9 (brokerage_note_id), UNIQUE INDEX line_per_brokerage_note (brokerage_note_id, line), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_OPERATION_ASSET_ID FOREIGN KEY (asset_id) REFERENCES asset (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_OPERATION_BROKERAGE_NOTE_ID FOREIGN KEY (brokerage_note_id) REFERENCES brokerage_note (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE operation');
        $this->addSql('ALTER TABLE brokerage_note CHANGE date date DATE NOT NULL');
    }
}
