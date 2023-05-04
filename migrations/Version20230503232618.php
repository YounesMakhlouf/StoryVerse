<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503232618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE story ADD slug VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL, DROP description, DROP last_modified_at, CHANGE language language VARCHAR(25) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE status status VARCHAR(25) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB560438989D9B62 ON story (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EB560438989D9B62 ON story');
        $this->addSql('ALTER TABLE story ADD description LONGTEXT DEFAULT NULL, ADD last_modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP slug, DROP updated_at, CHANGE language language VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
