<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827092200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE palette DROP FOREIGN KEY FK_C7E5A77EE340FC6D');
        $this->addSql('DROP INDEX IDX_C7E5A77EE340FC6D ON palette');
        $this->addSql('ALTER TABLE palette DROP logs_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE palette ADD logs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE palette ADD CONSTRAINT FK_C7E5A77EE340FC6D FOREIGN KEY (logs_id) REFERENCES logs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C7E5A77EE340FC6D ON palette (logs_id)');
    }
}
