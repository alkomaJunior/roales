<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706100222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurants (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, num_reviews INT NOT NULL, photo_small_url VARCHAR(255) DEFAULT NULL, photo_thumbnail_url VARCHAR(255) DEFAULT NULL, photo_original_url VARCHAR(255) DEFAULT NULL, photo_large_url VARCHAR(255) DEFAULT NULL, photo_medium_url VARCHAR(255) DEFAULT NULL, category_key VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_AD83772464D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurants ADD CONSTRAINT FK_AD83772464D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('DROP TABLE internal_app_info');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('ALTER TABLE locations ADD iso_two VARCHAR(255) NOT NULL, ADD iso_three VARCHAR(255) NOT NULL, DROP iso2, DROP iso3');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE internal_app_info (id INT AUTO_INCREMENT NOT NULL, all_restaurant TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, num_reviews INT NOT NULL, photo_small_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, photo_thumbnail_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, photo_original_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, photo_large_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, photo_medium_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, category_key VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, website VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, city_ascii VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE restaurants');
        $this->addSql('ALTER TABLE locations ADD iso2 VARCHAR(255) NOT NULL, ADD iso3 VARCHAR(255) NOT NULL, DROP iso_two, DROP iso_three');
    }
}
