<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312214512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_employees (company_id INT NOT NULL, employee_id INT NOT NULL, PRIMARY KEY(company_id, employee_id))');
        $this->addSql('CREATE INDEX IDX_899949F0979B1AD6 ON company_employees (company_id)');
        $this->addSql('CREATE INDEX IDX_899949F08C03F15C ON company_employees (employee_id)');
        $this->addSql('CREATE TABLE employees (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C300E7927C74 ON employees (email)');
        $this->addSql('COMMENT ON COLUMN employees.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE company_employees ADD CONSTRAINT FK_899949F0979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_employees ADD CONSTRAINT FK_899949F08C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE companies ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE companies DROP roles');
        $this->addSql('ALTER TABLE companies DROP password');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES employees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8244AA3A7E3C61F9 ON companies (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE companies DROP CONSTRAINT FK_8244AA3A7E3C61F9');
        $this->addSql('ALTER TABLE company_employees DROP CONSTRAINT FK_899949F0979B1AD6');
        $this->addSql('ALTER TABLE company_employees DROP CONSTRAINT FK_899949F08C03F15C');
        $this->addSql('DROP TABLE company_employees');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP INDEX IDX_8244AA3A7E3C61F9');
        $this->addSql('ALTER TABLE companies ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE companies ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE companies DROP owner_id');
    }
}
