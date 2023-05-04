<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504212521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_follow (user_id INT NOT NULL, following_id INT NOT NULL, INDEX IDX_D665F4DA76ED395 (user_id), INDEX IDX_D665F4D1816E3A3 (following_id), PRIMARY KEY(user_id, following_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4D1816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD bio VARCHAR(150) DEFAULT NULL, CHANGE biography avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_follow DROP FOREIGN KEY FK_D665F4DA76ED395');
        $this->addSql('ALTER TABLE user_follow DROP FOREIGN KEY FK_D665F4D1816E3A3');
        $this->addSql('DROP TABLE user_follow');
        $this->addSql('ALTER TABLE user DROP bio, CHANGE avatar biography VARCHAR(255) DEFAULT NULL');
    }
}
