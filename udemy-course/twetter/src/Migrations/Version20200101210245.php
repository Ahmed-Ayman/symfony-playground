<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200101210245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD confirmation_token VARCHAR(255) DEFAULT NULL, ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE micro_post CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE micro_post_id micro_post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL, CHANGE seen seen TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE micro_post CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE micro_post_id micro_post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL, CHANGE seen seen TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user DROP confirmation_token, DROP enabled');
    }
}
