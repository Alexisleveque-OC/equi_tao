<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417183853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669D86650F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669777D11E');
        $this->addSql('DROP INDEX IDX_23A0E669D86650F ON article');
        $this->addSql('DROP INDEX IDX_23A0E669777D11E ON article');
        $this->addSql('ALTER TABLE article ADD user_id INT NOT NULL, ADD category_id INT NOT NULL, DROP user_id_id, DROP category_id_id, CHANGE last_upsate_date last_update_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES article_category (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('CREATE INDEX IDX_23A0E6612469DE2 ON article (category_id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8F3EC46');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9D86650F');
        $this->addSql('DROP INDEX IDX_9474526C9D86650F ON comment');
        $this->addSql('DROP INDEX IDX_9474526C8F3EC46 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE user_id_id user_id INT DEFAULT NULL, CHANGE article_id_id article_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C7294869C ON comment (article_id)');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3C68922B3');
        $this->addSql('DROP INDEX IDX_F87474F3C68922B3 ON lesson');
        $this->addSql('ALTER TABLE lesson CHANGE day_id_id day_id INT NOT NULL');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F39C24126 FOREIGN KEY (day_id) REFERENCES day_planning (id)');
        $this->addSql('CREATE INDEX IDX_F87474F39C24126 ON lesson (day_id)');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B38AFFF611');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B39D86650F');
        $this->addSql('DROP INDEX IDX_AC6340B38AFFF611 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B39D86650F ON `like`');
        $this->addSql('ALTER TABLE `like` ADD user_id INT NOT NULL, ADD artcile_id INT NOT NULL, DROP user_id_id, DROP artcile_id_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3777FFAB4 FOREIGN KEY (artcile_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON `like` (user_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3777FFAB4 ON `like` (artcile_id)');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF69D86650F');
        $this->addSql('DROP INDEX IDX_D499BFF69D86650F ON planning');
        $this->addSql('ALTER TABLE planning CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D499BFF6A76ED395 ON planning (user_id)');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399777D11E');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F399D86650F');
        $this->addSql('DROP INDEX IDX_DFEC3F399777D11E ON rate');
        $this->addSql('DROP INDEX IDX_DFEC3F399D86650F ON rate');
        $this->addSql('ALTER TABLE rate ADD user_id INT NOT NULL, ADD category_id INT NOT NULL, DROP user_id_id, DROP category_id_id');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3912469DE2 FOREIGN KEY (category_id) REFERENCES rate_category (id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F39A76ED395 ON rate (user_id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F3912469DE2 ON rate (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('DROP INDEX IDX_23A0E6612469DE2 ON article');
        $this->addSql('ALTER TABLE article ADD user_id_id INT NOT NULL, ADD category_id_id INT NOT NULL, DROP user_id, DROP category_id, CHANGE last_update_date last_upsate_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669777D11E FOREIGN KEY (category_id_id) REFERENCES article_category (id)');
        $this->addSql('CREATE INDEX IDX_23A0E669D86650F ON article (user_id_id)');
        $this->addSql('CREATE INDEX IDX_23A0E669777D11E ON article (category_id_id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C7294869C ON comment');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id_id INT DEFAULT NULL, CHANGE article_id article_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C9D86650F ON comment (user_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526C8F3EC46 ON comment (article_id_id)');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A76ED395');
        $this->addSql('DROP INDEX IDX_D499BFF6A76ED395 ON planning');
        $this->addSql('ALTER TABLE planning CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF69D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D499BFF69D86650F ON planning (user_id_id)');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3777FFAB4');
        $this->addSql('DROP INDEX IDX_AC6340B3A76ED395 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B3777FFAB4 ON `like`');
        $this->addSql('ALTER TABLE `like` ADD user_id_id INT NOT NULL, ADD artcile_id_id INT NOT NULL, DROP user_id, DROP artcile_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B38AFFF611 FOREIGN KEY (artcile_id_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B38AFFF611 ON `like` (artcile_id_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B39D86650F ON `like` (user_id_id)');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F39A76ED395');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3912469DE2');
        $this->addSql('DROP INDEX IDX_DFEC3F39A76ED395 ON rate');
        $this->addSql('DROP INDEX IDX_DFEC3F3912469DE2 ON rate');
        $this->addSql('ALTER TABLE rate ADD user_id_id INT NOT NULL, ADD category_id_id INT NOT NULL, DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399777D11E FOREIGN KEY (category_id_id) REFERENCES rate_category (id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F399D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F399777D11E ON rate (category_id_id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F399D86650F ON rate (user_id_id)');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F39C24126');
        $this->addSql('DROP INDEX IDX_F87474F39C24126 ON lesson');
        $this->addSql('ALTER TABLE lesson CHANGE day_id day_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3C68922B3 FOREIGN KEY (day_id_id) REFERENCES day_planning (id)');
        $this->addSql('CREATE INDEX IDX_F87474F3C68922B3 ON lesson (day_id_id)');
    }
}
