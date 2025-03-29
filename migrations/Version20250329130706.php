<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329130706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advertisements (id SERIAL NOT NULL, company_id INT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, service_area TEXT NOT NULL, services_offered TEXT NOT NULL, status VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C755F1E979B1AD6 ON advertisements (company_id)');
        $this->addSql('CREATE INDEX IDX_5C755F1EB03A8386 ON advertisements (created_by_id)');
        $this->addSql('COMMENT ON COLUMN advertisements.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN advertisements.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1E979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1EB03A8386 FOREIGN KEY (created_by_id) REFERENCES employees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE advertisements DROP CONSTRAINT FK_5C755F1E979B1AD6');
        $this->addSql('ALTER TABLE advertisements DROP CONSTRAINT FK_5C755F1EB03A8386');
        $this->addSql('DROP TABLE advertisements');
    }
}
