<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504105018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935B4296D31F');
        $this->addSql('ALTER TABLE story_genre DROP FOREIGN KEY FK_5F10935BAA5D4036');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE story_genre');
        $this->addSql('ALTER TABLE story ADD genre VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD contribution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649FE5E5FBD ON user (contribution_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_835033F8989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE story_genre (story_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_5F10935B4296D31F (genre_id), INDEX IDX_5F10935BAA5D4036 (story_id), PRIMARY KEY(story_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935B4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_genre ADD CONSTRAINT FK_5F10935BAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story DROP genre');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE5E5FBD');
        $this->addSql('DROP INDEX IDX_8D93D649FE5E5FBD ON user');
        $this->addSql('ALTER TABLE user DROP contribution_id');
    }
}
