<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191018212907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE trick_id trick_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture CHANGE trick_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile CHANGE user_id user_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trick CHANGE date_update date_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE trick_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture CHANGE trick_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile CHANGE user_id user_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE trick CHANGE date_update date_update DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id INT DEFAULT NULL');
    }
}
