<?php

declare(strict_types=1);

namespace TheDevs\WMS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019111500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD cash_on_delivery DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD payment_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD delivery_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD carrier VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD delivery_address JSONB NOT NULL');
        $this->addSql('ALTER TABLE order_history ALTER from_status DROP NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD ean VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD sku VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD serial_numbers JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ALTER product_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_history ALTER from_status SET NOT NULL');
        $this->addSql('ALTER TABLE order_item DROP title');
        $this->addSql('ALTER TABLE order_item DROP ean');
        $this->addSql('ALTER TABLE order_item DROP price');
        $this->addSql('ALTER TABLE order_item DROP sku');
        $this->addSql('ALTER TABLE order_item DROP serial_numbers');
        $this->addSql('ALTER TABLE order_item ALTER product_id SET NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP price');
        $this->addSql('ALTER TABLE "order" DROP cash_on_delivery');
        $this->addSql('ALTER TABLE "order" DROP payment_price');
        $this->addSql('ALTER TABLE "order" DROP delivery_price');
        $this->addSql('ALTER TABLE "order" DROP carrier');
        $this->addSql('ALTER TABLE "order" DROP delivery_address');
    }
}
