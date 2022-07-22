<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720023809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attraction (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, num_reviews INT NOT NULL, photo_small_url VARCHAR(255) DEFAULT NULL, photo_thumbnail_url VARCHAR(255) DEFAULT NULL, photo_original_url VARCHAR(255) DEFAULT NULL, photo_large_url VARCHAR(255) DEFAULT NULL, photo_medium_url VARCHAR(255) DEFAULT NULL, category_key VARCHAR(255) NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, web_url VARCHAR(255) DEFAULT NULL, web_review VARCHAR(255) DEFAULT NULL, INDEX IDX_D503E6B864D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, num_reviews INT NOT NULL, photo_small_url VARCHAR(255) DEFAULT NULL, photo_thumbnail_url VARCHAR(255) DEFAULT NULL, photo_original_url VARCHAR(255) DEFAULT NULL, photo_large_url VARCHAR(255) DEFAULT NULL, photo_medium_url VARCHAR(255) DEFAULT NULL, category_key VARCHAR(255) NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, slug VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, web_url VARCHAR(255) DEFAULT NULL, web_review VARCHAR(255) DEFAULT NULL, INDEX IDX_3535ED964D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attraction ADD CONSTRAINT FK_D503E6B864D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED964D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE attraction');
        $this->addSql('DROP TABLE hotel');
    }
}
