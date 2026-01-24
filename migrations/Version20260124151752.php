<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260124151752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE environnement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE voyage ADD environnement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D8955BAFB82A1 FOREIGN KEY (environnement_id) REFERENCES environnement (id)');
        $this->addSql('CREATE INDEX IDX_3F9D8955BAFB82A1 ON voyage (environnement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE environnement');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D8955BAFB82A1');
        $this->addSql('DROP INDEX IDX_3F9D8955BAFB82A1 ON voyage');
        $this->addSql('ALTER TABLE voyage DROP environnement_id');
    }
}
