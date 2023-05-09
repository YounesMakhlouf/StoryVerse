<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509102129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_835033F8989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story_genre (story_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_5F10935BAA5D4036 (story_id), INDEX IDX_5F10935B4296D31F (genre_id), PRIMARY KEY(story_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935BAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935B4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CACD53EDB6');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF624B39D');
        $this->addSql('ALTER TABLE story_user DROP FOREIGN KEY FK_1B3EBA67A76ED395');
        $this->addSql('ALTER TABLE story_user DROP FOREIGN KEY FK_1B3EBA67AA5D4036');
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345F7A2C2FC');
        $this->addSql('ALTER TABLE user_badge DROP FOREIGN KEY FK_1C32B345A76ED395');
        $this->addSql('ALTER TABLE user_quest DROP FOREIGN KEY FK_A1D5034F209E9EF4');
        $this->addSql('ALTER TABLE user_quest DROP FOREIGN KEY FK_A1D5034FA76ED395');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE quest');
        $this->addSql('DROP TABLE story_user');
        $this->addSql('DROP TABLE user_badge');
        $this->addSql('DROP TABLE user_quest');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAA5D4036');
        $this->addSql('DROP INDEX IDX_9474526CF8697D13 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CAA5D4036 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B ON comment');
        $this->addSql('ALTER TABLE comment ADD reply_id INT DEFAULT NULL, DROP story_id, DROP author_id, DROP comment_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8A0E4E7F FOREIGN KEY (reply_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_9474526C8A0E4E7F ON comment (reply_id)');
        $this->addSql('ALTER TABLE competition ADD image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15F675F31B');
        $this->addSql('DROP INDEX IDX_EA351E15F675F31B ON contribution');
        $this->addSql('ALTER TABLE contribution ADD reported TINYINT(1) NOT NULL, CHANGE story_id story_id INT NOT NULL, CHANGE author_id likes INT NOT NULL');
        $this->addSql('ALTER TABLE story ADD likes INT NOT NULL, DROP genre, DROP is_reported, DROP story_image');
        $this->addSql('ALTER TABLE user DROP bio, DROP csrf_token, DROP date_of_birth, DROP country, DROP xp, CHANGE avatar biography VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, required_xp INT NOT NULL, image LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, content VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_BF5476CAF624B39D (sender_id), INDEX IDX_BF5476CACD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quest (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, points INT NOT NULL, type VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, requirement VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE story_user (story_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1B3EBA67A76ED395 (user_id), INDEX IDX_1B3EBA67AA5D4036 (story_id), PRIMARY KEY(story_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_badge (user_id INT NOT NULL, badge_id INT NOT NULL, INDEX IDX_1C32B345A76ED395 (user_id), INDEX IDX_1C32B345F7A2C2FC (badge_id), PRIMARY KEY(user_id, badge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_quest (user_id INT NOT NULL, quest_id INT NOT NULL, INDEX IDX_A1D5034FA76ED395 (user_id), INDEX IDX_A1D5034F209E9EF4 (quest_id), PRIMARY KEY(user_id, quest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE story_user ADD CONSTRAINT FK_1B3EBA67A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_user ADD CONSTRAINT FK_1B3EBA67AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_badge ADD CONSTRAINT FK_1C32B345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034F209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_quest ADD CONSTRAINT FK_A1D5034FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935BAA5D4036');
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935B4296D31F');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE story_genre');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8A0E4E7F');
        $this->addSql('DROP INDEX IDX_9474526C8A0E4E7F ON comment');
        $this->addSql('ALTER TABLE comment ADD author_id INT NOT NULL, ADD comment_id INT DEFAULT NULL, CHANGE reply_id story_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('CREATE INDEX IDX_9474526CF8697D13 ON comment (comment_id)');
        $this->addSql('CREATE INDEX IDX_9474526CAA5D4036 ON comment (story_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('ALTER TABLE competition DROP image_filename');
        $this->addSql('ALTER TABLE contribution DROP reported, CHANGE story_id story_id INT DEFAULT NULL, CHANGE likes author_id INT NOT NULL');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EA351E15F675F31B ON contribution (author_id)');
        $this->addSql('ALTER TABLE story ADD genre VARCHAR(25) DEFAULT NULL, ADD is_reported TINYINT(1) DEFAULT NULL, ADD story_image VARCHAR(255) DEFAULT NULL, DROP likes');
        $this->addSql('ALTER TABLE user ADD bio VARCHAR(150) DEFAULT NULL, ADD csrf_token VARCHAR(255) NOT NULL, ADD date_of_birth DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD country VARCHAR(30) DEFAULT NULL, ADD xp INT NOT NULL, CHANGE biography avatar VARCHAR(255) DEFAULT NULL');
    }
}
