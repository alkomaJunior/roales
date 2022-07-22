<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721124004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attraction_user (attraction_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FD1C74603C216F9D (attraction_id), INDEX IDX_FD1C7460A76ED395 (user_id), PRIMARY KEY(attraction_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attraction_user ADD CONSTRAINT FK_FD1C74603C216F9D FOREIGN KEY (attraction_id) REFERENCES attraction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attraction_user ADD CONSTRAINT FK_FD1C7460A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attraction DROP user');
        $this->addSql('ALTER TABLE users DROP attractions');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE attraction_user');
        $this->addSql('ALTER TABLE attraction ADD user VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD attractions VARCHAR(255) DEFAULT NULL');
    }
}
