<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191225135012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2AEFE017A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__micro_post AS SELECT id, user_id, text, time FROM micro_post');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('CREATE TABLE micro_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, text VARCHAR(280) NOT NULL COLLATE BINARY, time DATETIME NOT NULL)');
        $this->addSql('INSERT INTO micro_post (id, user_id, text, time) SELECT id, user_id, text, time FROM __temp__micro_post');
        $this->addSql('DROP TABLE __temp__micro_post');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, email, full_name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, full_name VARCHAR(50) NOT NULL COLLATE BINARY, roles CLOB NOT NULL --(DC2Type:simple_array)
        )');
        $this->addSql('INSERT INTO user (id, username, roles, password, email, full_name) SELECT id, username, roles, password, email, full_name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
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
        $this->addSql('CREATE INDEX IDX_2AEFE017A76ED395 ON micro_post (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, email, full_name, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(50) NOT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, username, password, email, full_name, roles) SELECT id, username, password, email, full_name, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
