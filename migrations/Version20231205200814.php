<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205200814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE creation_date creation_date DATETIME NOT NULL, CHANGE last_update_date last_update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE creation_date creation_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE creation_date creation_date DATE NOT NULL, CHANGE last_update_date last_update_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE creation_date creation_date DATE NOT NULL');
    }
}
