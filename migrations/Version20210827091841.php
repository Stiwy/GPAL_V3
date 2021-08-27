<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827091841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logs DROP FOREIGN KEY FK_F08FC65C908BC74');
        $this->addSql('DROP INDEX IDX_F08FC65C908BC74 ON logs');
        $this->addSql('ALTER TABLE logs DROP palette_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logs ADD palette_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE logs ADD CONSTRAINT FK_F08FC65C908BC74 FOREIGN KEY (palette_id) REFERENCES palette (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F08FC65C908BC74 ON logs (palette_id)');
    }
}
