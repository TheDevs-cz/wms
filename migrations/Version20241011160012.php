<?php

declare(strict_types=1);

namespace TheDevs\WMS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011160012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, warehouse_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CB5080ECDE ON location (warehouse_id)');
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, ordered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, number VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)');
        $this->addSql('CREATE TABLE order_history (id UUID NOT NULL, happened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, from_status VARCHAR(255) NOT NULL, to_status VARCHAR(255) NOT NULL, order_id UUID NOT NULL, author_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1C0D9008D9F6D38 ON order_history (order_id)');
        $this->addSql('CREATE INDEX IDX_D1C0D900F675F31B ON order_history (author_id)');
        $this->addSql('CREATE TABLE order_item (id UUID NOT NULL, quantity INT NOT NULL, order_id UUID NOT NULL, product_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('CREATE TABLE product (id UUID NOT NULL, sku VARCHAR(255) NOT NULL, ean VARCHAR(255) NOT NULL, imported_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADA76ED395 ON product (user_id)');
        $this->addSql('CREATE TABLE product_stock_movement (id UUID NOT NULL, moved_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author_id UUID NOT NULL, product_id UUID NOT NULL, from_location_id UUID DEFAULT NULL, to_location_id UUID DEFAULT NULL, order_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E872BF99F675F31B ON product_stock_movement (author_id)');
        $this->addSql('CREATE INDEX IDX_E872BF994584665A ON product_stock_movement (product_id)');
        $this->addSql('CREATE INDEX IDX_E872BF99980210EB ON product_stock_movement (from_location_id)');
        $this->addSql('CREATE INDEX IDX_E872BF9928DE1FED ON product_stock_movement (to_location_id)');
        $this->addSql('CREATE INDEX IDX_E872BF998D9F6D38 ON product_stock_movement (order_id)');
        $this->addSql('CREATE TABLE stock_item (id UUID NOT NULL, stocked_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, product_id UUID NOT NULL, location_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6017DDA4584665A ON stock_item (product_id)');
        $this->addSql('CREATE INDEX IDX_6017DDA64D218E ON stock_item (location_id)');
        $this->addSql('CREATE TABLE warehouse (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ECB38BFCA76ED395 ON warehouse (user_id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D9008D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D900F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF99F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF994584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF99980210EB FOREIGN KEY (from_location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF9928DE1FED FOREIGN KEY (to_location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF998D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT FK_6017DDA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT FK_6017DDA64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE warehouse ADD CONSTRAINT FK_ECB38BFCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP avatar');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB5080ECDE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT FK_D1C0D9008D9F6D38');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT FK_D1C0D900F675F31B');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF99F675F31B');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF994584665A');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF99980210EB');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF9928DE1FED');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF998D9F6D38');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT FK_6017DDA4584665A');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT FK_6017DDA64D218E');
        $this->addSql('ALTER TABLE warehouse DROP CONSTRAINT FK_ECB38BFCA76ED395');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_history');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_stock_movement');
        $this->addSql('DROP TABLE stock_item');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('ALTER TABLE "user" ADD avatar VARCHAR(255) DEFAULT NULL');
    }
}
