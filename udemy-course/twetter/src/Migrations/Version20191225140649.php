<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191225140649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, user_id, text, time FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, text VARCHAR(280) NOT NULL COLLATE BINARY, time DATETIME NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO micro_post (id, user_id, text, time) SELECT id, user_id, text, time FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, text, time, user_id FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, text VARCHAR(280) NOT NULL, time DATETIME NOT NULL, user_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO micro_post (id, text, time, user_id) SELECT id, text, time, user_id FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
    }
}
