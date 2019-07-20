<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190720004255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE task ADD COLUMN exclude_dir CLOB DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_385B5AA18DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task_history AS SELECT id, task_id, report_dir, create_at FROM task_history');
        $this->addSql('DROP TABLE task_history');
        $this->addSql('CREATE TABLE task_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER DEFAULT NULL, report_dir VARCHAR(255) NOT NULL COLLATE BINARY, create_at DATETIME NOT NULL, CONSTRAINT FK_385B5AA18DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task_history (id, task_id, report_dir, create_at) SELECT id, task_id, report_dir, create_at FROM __temp__task_history');
        $this->addSql('DROP TABLE __temp__task_history');
        $this->addSql('CREATE INDEX IDX_385B5AA18DB60186 ON task_history (task_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, name, host, dir, status FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, host VARCHAR(150) DEFAULT NULL, dir VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL)');
        $this->addSql('INSERT INTO task (id, name, host, dir, status) SELECT id, name, host, dir, status FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('DROP INDEX IDX_385B5AA18DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task_history AS SELECT id, task_id, report_dir, create_at FROM task_history');
        $this->addSql('DROP TABLE task_history');
        $this->addSql('CREATE TABLE task_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER DEFAULT NULL, report_dir VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO task_history (id, task_id, report_dir, create_at) SELECT id, task_id, report_dir, create_at FROM __temp__task_history');
        $this->addSql('DROP TABLE __temp__task_history');
        $this->addSql('CREATE INDEX IDX_385B5AA18DB60186 ON task_history (task_id)');
    }
}
