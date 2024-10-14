<?php

declare(strict_types=1);

namespace TheDevs\WMS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014220901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT fk_bb1bc1b5980210eb');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT fk_bb1bc1b528de1fed');
        $this->addSql('DROP INDEX idx_bb1bc1b528de1fed');
        $this->addSql('DROP INDEX idx_bb1bc1b5980210eb');
        $this->addSql('ALTER TABLE stock_movement ADD from_position_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_movement ADD to_position_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_movement DROP from_location_id');
        $this->addSql('ALTER TABLE stock_movement DROP to_location_id');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B543CB1F23 FOREIGN KEY (from_position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B5F3171025 FOREIGN KEY (to_position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BB1BC1B543CB1F23 ON stock_movement (from_position_id)');
        $this->addSql('CREATE INDEX IDX_BB1BC1B5F3171025 ON stock_movement (to_position_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B543CB1F23');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B5F3171025');
        $this->addSql('DROP INDEX IDX_BB1BC1B543CB1F23');
        $this->addSql('DROP INDEX IDX_BB1BC1B5F3171025');
        $this->addSql('ALTER TABLE stock_movement ADD from_location_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_movement ADD to_location_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_movement DROP from_position_id');
        $this->addSql('ALTER TABLE stock_movement DROP to_position_id');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT fk_bb1bc1b5980210eb FOREIGN KEY (from_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT fk_bb1bc1b528de1fed FOREIGN KEY (to_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_bb1bc1b528de1fed ON stock_movement (to_location_id)');
        $this->addSql('CREATE INDEX idx_bb1bc1b5980210eb ON stock_movement (from_location_id)');
    }
}
