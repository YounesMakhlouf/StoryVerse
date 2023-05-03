<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502153550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE reported reported TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE competition CHANGE status status VARCHAR(25) NOT NULL, CHANGE is_paid paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE contribution CHANGE reported reported TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE favourites_list CHANGE is_public public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(25) NOT NULL, CHANGE slug slug VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE story DROP description, CHANGE language language VARCHAR(25) NOT NULL, CHANGE status status VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE reported reported TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE competition CHANGE status status VARCHAR(255) NOT NULL, CHANGE paid is_paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE contribution CHANGE reported reported TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE favourites_list CHANGE public is_public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE story ADD description LONGTEXT DEFAULT NULL, CHANGE language language VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
