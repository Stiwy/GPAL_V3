<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823132035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, palette_id INT NOT NULL, action VARCHAR(255) NOT NULL, info VARCHAR(255) NOT NULL, date_insert DATETIME NOT NULL, INDEX IDX_F08FC65CA76ED395 (user_id), INDEX IDX_F08FC65C908BC74 (palette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE logs ADD CONSTRAINT FK_F08FC65CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE logs ADD CONSTRAINT FK_F08FC65C908BC74 FOREIGN KEY (palette_id) REFERENCES palette (id)');
        $this->addSql('ALTER TABLE palette DROP FOREIGN KEY FK_C7E5A77EA76ED395');
        $this->addSql('DROP INDEX IDX_C7E5A77EA76ED395 ON palette');
        $this->addSql('ALTER TABLE palette DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE logs');
        $this->addSql('ALTER TABLE palette ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE palette ADD CONSTRAINT FK_C7E5A77EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C7E5A77EA76ED395 ON palette (user_id)');
    }
}
