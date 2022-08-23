<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823075110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "campervans" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "demand_items" (id SERIAL NOT NULL, demand_id INT NOT NULL, equipment_id INT NOT NULL, qty INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E7FCF485D022E59 ON "demand_items" (demand_id)');
        $this->addSql('CREATE INDEX IDX_7E7FCF48517FE9FE ON "demand_items" (equipment_id)');
        $this->addSql('CREATE TABLE "demands" (id SERIAL NOT NULL, campervan_id INT NOT NULL, start_station_id INT NOT NULL, end_station_id INT NOT NULL, customer_id INT NOT NULL, rental_start DATE NOT NULL, rental_end DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D24062F4B9D53E94 ON "demands" (campervan_id)');
        $this->addSql('CREATE INDEX IDX_D24062F453721DCB ON "demands" (start_station_id)');
        $this->addSql('CREATE INDEX IDX_D24062F42FF5EABB ON "demands" (end_station_id)');
        $this->addSql('CREATE INDEX IDX_D24062F49395C3F3 ON "demands" (customer_id)');
        $this->addSql('CREATE TABLE "equipments" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "stations" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "users" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('ALTER TABLE "demand_items" ADD CONSTRAINT FK_7E7FCF485D022E59 FOREIGN KEY (demand_id) REFERENCES "demands" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "demand_items" ADD CONSTRAINT FK_7E7FCF48517FE9FE FOREIGN KEY (equipment_id) REFERENCES "equipments" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "demands" ADD CONSTRAINT FK_D24062F4B9D53E94 FOREIGN KEY (campervan_id) REFERENCES "campervans" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "demands" ADD CONSTRAINT FK_D24062F453721DCB FOREIGN KEY (start_station_id) REFERENCES "stations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "demands" ADD CONSTRAINT FK_D24062F42FF5EABB FOREIGN KEY (end_station_id) REFERENCES "stations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "demands" ADD CONSTRAINT FK_D24062F49395C3F3 FOREIGN KEY (customer_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "demand_items" DROP CONSTRAINT FK_7E7FCF485D022E59');
        $this->addSql('ALTER TABLE "demand_items" DROP CONSTRAINT FK_7E7FCF48517FE9FE');
        $this->addSql('ALTER TABLE "demands" DROP CONSTRAINT FK_D24062F4B9D53E94');
        $this->addSql('ALTER TABLE "demands" DROP CONSTRAINT FK_D24062F453721DCB');
        $this->addSql('ALTER TABLE "demands" DROP CONSTRAINT FK_D24062F42FF5EABB');
        $this->addSql('ALTER TABLE "demands" DROP CONSTRAINT FK_D24062F49395C3F3');
        $this->addSql('DROP TABLE "campervans"');
        $this->addSql('DROP TABLE "demand_items"');
        $this->addSql('DROP TABLE "demands"');
        $this->addSql('DROP TABLE "equipments"');
        $this->addSql('DROP TABLE "stations"');
        $this->addSql('DROP TABLE "users"');
    }
}
