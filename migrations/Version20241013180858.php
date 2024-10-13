<?php

declare(strict_types=1);

namespace TheDevs\WMS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013180858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position (deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, location_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_462CE4F564D218E ON position (location_id)');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F564D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB5080ECDE');
        $this->addSql('ALTER TABLE location ADD deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT FK_D1C0D9008D9F6D38');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT FK_D1C0D900F675F31B');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D9008D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D900F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE password_reset_token DROP CONSTRAINT FK_6B7BA4B6A76ED395');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE product ADD deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF99F675F31B');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF994584665A');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF99980210EB');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF9928DE1FED');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT FK_E872BF998D9F6D38');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF99F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF994584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF99980210EB FOREIGN KEY (from_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF9928DE1FED FOREIGN KEY (to_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT FK_E872BF998D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT fk_6017dda64d218e');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT FK_6017DDA4584665A');
        $this->addSql('DROP INDEX idx_6017dda64d218e');
        $this->addSql('ALTER TABLE stock_item ADD deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_item ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE stock_item ADD sku VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_item ADD ean VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE stock_item ALTER product_id DROP NOT NULL');
        $this->addSql('ALTER TABLE stock_item RENAME COLUMN location_id TO position_id');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT FK_6017DDADD842E46 FOREIGN KEY (position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT FK_6017DDA4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6017DDADD842E46 ON stock_item (position_id)');
        $this->addSql('ALTER TABLE "user" ADD deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE warehouse DROP CONSTRAINT fk_ecb38bfca76ed395');
        $this->addSql('DROP INDEX idx_ecb38bfca76ed395');
        $this->addSql('ALTER TABLE warehouse ADD deactivated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE warehouse DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position DROP CONSTRAINT FK_462CE4F564D218E');
        $this->addSql('DROP TABLE position');
        $this->addSql('ALTER TABLE warehouse ADD user_id UUID NOT NULL');
        $this->addSql('ALTER TABLE warehouse DROP deactivated_at');
        $this->addSql('ALTER TABLE warehouse ADD CONSTRAINT fk_ecb38bfca76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ecb38bfca76ed395 ON warehouse (user_id)');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf99f675f31b');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf994584665a');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf99980210eb');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf9928de1fed');
        $this->addSql('ALTER TABLE product_stock_movement DROP CONSTRAINT fk_e872bf998d9f6d38');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf99f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf994584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf99980210eb FOREIGN KEY (from_location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf9928de1fed FOREIGN KEY (to_location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_stock_movement ADD CONSTRAINT fk_e872bf998d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT FK_6017DDADD842E46');
        $this->addSql('ALTER TABLE stock_item DROP CONSTRAINT fk_6017dda4584665a');
        $this->addSql('DROP INDEX IDX_6017DDADD842E46');
        $this->addSql('ALTER TABLE stock_item DROP deactivated_at');
        $this->addSql('ALTER TABLE stock_item DROP quantity');
        $this->addSql('ALTER TABLE stock_item DROP sku');
        $this->addSql('ALTER TABLE stock_item DROP ean');
        $this->addSql('ALTER TABLE stock_item ALTER product_id SET NOT NULL');
        $this->addSql('ALTER TABLE stock_item RENAME COLUMN position_id TO location_id');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT fk_6017dda64d218e FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock_item ADD CONSTRAINT fk_6017dda4584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6017dda64d218e ON stock_item (location_id)');
        $this->addSql('ALTER TABLE password_reset_token DROP CONSTRAINT fk_6b7ba4b6a76ed395');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT fk_6b7ba4b6a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398a76ed395');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP deactivated_at');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT fk_d1c0d9008d9f6d38');
        $this->addSql('ALTER TABLE order_history DROP CONSTRAINT fk_d1c0d900f675f31b');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT fk_d1c0d9008d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT fk_d1c0d900f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cb5080ecde');
        $this->addSql('ALTER TABLE location DROP deactivated_at');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cb5080ecde FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f098d9f6d38');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f094584665a');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f094584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ada76ed395');
        $this->addSql('ALTER TABLE product DROP deactivated_at');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ada76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
