<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501174914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD updated_at DATETIME NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contribution ADD updated_at DATETIME NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE favourites_list ADD updated_at DATETIME NOT NULL, DROP last_modified_at, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE story ADD updated_at DATETIME NOT NULL, DROP last_modified_at, CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE contribution DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE favourites_list ADD last_modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE story ADD last_modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
