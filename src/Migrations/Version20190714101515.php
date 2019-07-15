<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190714101515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE host (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, host VARCHAR(150) NOT NULL, name VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, host VARCHAR(150) DEFAULT NULL, dir VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL)');
        $this->addSql('CREATE TABLE task_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task INTEGER NOT NULL, report_dir VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE host');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_history');
    }
}
