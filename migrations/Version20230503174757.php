<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503174757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE story_genre (story_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_5F10935BAA5D4036 (story_id), INDEX IDX_5F10935B4296D31F (genre_id), PRIMARY KEY(story_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_competition (user_id INT NOT NULL, competition_id INT NOT NULL, INDEX IDX_1C11E524A76ED395 (user_id), INDEX IDX_1C11E5247B39D312 (competition_id), PRIMARY KEY(user_id, competition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935BAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935B4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_competition ADD CONSTRAINT FK_1C11E524A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_competition ADD CONSTRAINT FK_1C11E5247B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438F675F31B');
        $this->addSql('DROP INDEX IDX_EB560438F675F31B ON story');
        $this->addSql('ALTER TABLE story DROP author_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935BAA5D4036');
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935B4296D31F');
        $this->addSql('ALTER TABLE user_competition DROP FOREIGN KEY FK_1C11E524A76ED395');
        $this->addSql('ALTER TABLE user_competition DROP FOREIGN KEY FK_1C11E5247B39D312');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('DROP TABLE story_genre');
        $this->addSql('DROP TABLE user_competition');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('ALTER TABLE story ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB560438F675F31B ON story (author_id)');
    }
}
