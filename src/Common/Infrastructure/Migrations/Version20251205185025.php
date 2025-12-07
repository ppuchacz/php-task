<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205185025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department ADD COLUMN bonus_amount INTEGER NOT NULL');
        $this->addSql('ALTER TABLE employee ADD COLUMN job_start_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__department AS SELECT id, name, bonus_type, max_bonus_years FROM department');
        $this->addSql('DROP TABLE department');
        $this->addSql('CREATE TABLE department (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, bonus_type VARCHAR(50) NOT NULL, max_bonus_years INTEGER NOT NULL)');
        $this->addSql('INSERT INTO department (id, name, bonus_type, max_bonus_years) SELECT id, name, bonus_type, max_bonus_years FROM __temp__department');
        $this->addSql('DROP TABLE __temp__department');
        $this->addSql('CREATE TEMPORARY TABLE __temp__employee AS SELECT id, department_id, name, surname, remuneration_base FROM employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('CREATE TABLE employee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, department_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, remuneration_base NUMERIC(2, 5) NOT NULL, CONSTRAINT FK_5D9F75A1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO employee (id, department_id, name, surname, remuneration_base) SELECT id, department_id, name, surname, remuneration_base FROM __temp__employee');
        $this->addSql('DROP TABLE __temp__employee');
        $this->addSql('CREATE INDEX IDX_5D9F75A1AE80F5DF ON employee (department_id)');
    }
}
