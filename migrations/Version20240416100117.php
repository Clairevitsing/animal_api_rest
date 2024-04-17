<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416100117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_country (animal_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_DCACA65C8E962C16 (animal_id), INDEX IDX_DCACA65CF92F3E70 (country_id), PRIMARY KEY(animal_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal_country ADD CONSTRAINT FK_DCACA65C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal_country ADD CONSTRAINT FK_DCACA65CF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal ADD average_size VARCHAR(255) NOT NULL, ADD average_lifespan VARCHAR(255) NOT NULL, ADD martial_art VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country ADD isocode VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_country DROP FOREIGN KEY FK_DCACA65C8E962C16');
        $this->addSql('ALTER TABLE animal_country DROP FOREIGN KEY FK_DCACA65CF92F3E70');
        $this->addSql('DROP TABLE animal_country');
        $this->addSql('ALTER TABLE animal DROP average_size, DROP average_lifespan, DROP martial_art, DROP phone_number');
        $this->addSql('ALTER TABLE country DROP isocode');
    }
}
