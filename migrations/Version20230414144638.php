<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414144638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, category_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, creation_date DATE NOT NULL, last_upsate_date DATE DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, INDEX IDX_23A0E669D86650F (user_id_id), INDEX IDX_23A0E669777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, article_id_id INT NOT NULL, creation_date DATE NOT NULL, content LONGTEXT NOT NULL, is_validate TINYINT(1) NOT NULL, INDEX IDX_9474526C9D86650F (user_id_id), INDEX IDX_9474526C8F3EC46 (article_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day_planning (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, day_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, begin TIME NOT NULL, end TIME NOT NULL, INDEX IDX_F87474F3C68922B3 (day_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, artcile_id_id INT NOT NULL, INDEX IDX_AC6340B39D86650F (user_id_id), INDEX IDX_AC6340B38AFFF611 (artcile_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, is_valid TINYINT(1) NOT NULL, number_of_day INT NOT NULL, creation_date DATE NOT NULL, last_update_date DATE NOT NULL, INDEX IDX_D499BFF69D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, category_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_DFEC3F399D86650F (user_id_id), INDEX IDX_DFEC3F399777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669777D11E FOREIGN KEY (category_id_id) REFERENCES article_category (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3C68922B3 FOREIGN KEY (day_id_id) REFERENCES day_planning (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B38AFFF611 FOREIGN KEY (artcile_id_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF69D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399777D11E FOREIGN KEY (category_id_id) REFERENCES rate_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669D86650F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669777D11E');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8F3EC46');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3C68922B3');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39D86650F');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B38AFFF611');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF69D86650F');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399D86650F');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399777D11E');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE day_planning');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE rate_category');
    }
}