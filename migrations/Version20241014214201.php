<?php

declare(strict_types=1);

namespace TheDevs\WMS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014214201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock_movement (id UUID NOT NULL, ean VARCHAR(255) NOT NULL, sku VARCHAR(255) DEFAULT NULL, moved_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author_id UUID NOT NULL, product_id UUID DEFAULT NULL, from_location_id UUID DEFAULT NULL, to_location_id UUID DEFAULT NULL, order_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BB1BC1B5F675F31B ON stock_movement (author_id)');
        $this->addSql('CREATE INDEX IDX_BB1BC1B54584665A ON stock_movement (product_id)');
        $this->addSql('CREATE INDEX IDX_BB1BC1B5980210EB ON stock_movement (from_location_id)');
        $this->addSql('CREATE INDEX IDX_BB1BC1B528DE1FED ON stock_movement (to_location_id)');
        $this->addSql('CREATE INDEX IDX_BB1BC1B58D9F6D38 ON stock_movement (order_id)');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B5F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B54584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B5980210EB FOREIGN KEY (from_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B528DE1FED FOREIGN KEY (to_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B58D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf99f675f31b');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf994584665a');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf99980210eb');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf9928de1fed');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf998d9f6d38');
        $this->addSql('DROP TABLE product_stock_movement');
        $this->addSql('ALTER TABLE stock_item ALTER ean SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_stock_movement (id UUID NOT NULL, moved_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author_id UUID NOT NULL, product_id UUID NOT NULL, from_location_id UUID DEFAULT NULL, to_location_id UUID DEFAULT NULL, order_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e872bf998d9f6d38 ON product_stock_movement (order_id)');
        $this->addSql('CREATE INDEX idx_e872bf9928de1fed ON product_stock_movement (to_location_id)');
        $this->addSql('CREATE INDEX idx_e872bf99980210eb ON product_stock_movement (from_location_id)');
        $this->addSql('CREATE INDEX idx_e872bf994584665a ON product_stock_movement (product_id)');
        $this->addSql('CREATE INDEX idx_e872bf99f675f31b ON product_stock_movement (author_id)');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf99f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf994584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf99980210eb FOREIGN KEY (from_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf9928de1fed FOREIGN KEY (to_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf998d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B5F675F31B');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B54584665A');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B5980210EB');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B528DE1FED');
        $this->addSql('ALTER TABLE stock_movement DROP CONSTRAINT FK_BB1BC1B58D9F6D38');
        $this->addSql('DROP TABLE stock_movement');
        $this->addSql('ALTER TABLE stock_item ALTER ean DROP NOT NULL');
    }
}
