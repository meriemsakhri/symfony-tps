<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251026231149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD COLUMN email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__job AS SELECT id, type, company, description, expires_at FROM job');
        $this->addSql('DROP TABLE job');
        $this->addSql('CREATE TABLE job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, company VARCHAR(255) NOT NULL, description CLOB NOT NULL, expires_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO job (id, type, company, description, expires_at) SELECT id, type, company, description, expires_at FROM __temp__job');
        $this->addSql('DROP TABLE __temp__job');
    }
}
