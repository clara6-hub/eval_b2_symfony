<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261015121028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, title, date, informations, category, number_of_places, image, is_validated FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, informations CLOB NOT NULL, category VARCHAR(255) NOT NULL, number_of_places INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, is_validated BOOLEAN DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO event (id, title, date, informations, category, number_of_places, image, is_validated) SELECT id, title, date, informations, category, number_of_places, image, is_validated FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, title, date, informations, category, number_of_places, image, is_validated FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, informations CLOB NOT NULL, category VARCHAR(255) NOT NULL, number_of_places INTEGER NOT NULL, image VARCHAR(255) NOT NULL, is_validated BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO event (id, title, date, informations, category, number_of_places, image, is_validated) SELECT id, title, date, informations, category, number_of_places, image, is_validated FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }
}
