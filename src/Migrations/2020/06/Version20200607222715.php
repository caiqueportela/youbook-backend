<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607222715 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_user_role_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_user_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_user_role (role_id INT NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(role_id))');
        $this->addSql('CREATE TABLE tb_user (user_id INT NOT NULL, username VARCHAR(25) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, activated BOOLEAN NOT NULL, locale VARCHAR(10) NOT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6E3D458F85E0677 ON tb_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6E3D458E7927C74 ON tb_user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tb_user_role_role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_user_user_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_user_role');
        $this->addSql('DROP TABLE tb_user');
    }
}
