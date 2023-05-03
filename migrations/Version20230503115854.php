<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503115854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD reply_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8A0E4E7F FOREIGN KEY (reply_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_9474526C8A0E4E7F ON comment (reply_id)');
        $this->addSql('ALTER TABLE contribution ADD story_id INT NOT NULL');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('CREATE INDEX IDX_EA351E15AA5D4036 ON contribution (story_id)');
        $this->addSql('ALTER TABLE story ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB560438F675F31B ON story (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8A0E4E7F');
        $this->addSql('DROP INDEX IDX_9474526C8A0E4E7F ON comment');
        $this->addSql('ALTER TABLE comment DROP reply_id');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15AA5D4036');
        $this->addSql('DROP INDEX IDX_EA351E15AA5D4036 ON contribution');
        $this->addSql('ALTER TABLE contribution DROP story_id');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438F675F31B');
        $this->addSql('DROP INDEX IDX_EB560438F675F31B ON story');
        $this->addSql('ALTER TABLE story DROP author_id');
    }
}
