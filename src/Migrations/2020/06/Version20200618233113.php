<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618233113 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_wallet_wallet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_address_address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_wallet (wallet_id INT NOT NULL, activated BOOLEAN DEFAULT \'true\' NOT NULL, balance NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, PRIMARY KEY(wallet_id))');
        $this->addSql('CREATE TABLE tb_address (address_id INT NOT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, adjunct VARCHAR(255) NOT NULL, zipcode INT NOT NULL, country VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(address_id))');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_user ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tb_user ADD CONSTRAINT FK_D6E3D458F5B7AF75 FOREIGN KEY (address_id) REFERENCES tb_address (address_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6E3D458F5B7AF75 ON tb_user (address_id)');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tb_user DROP CONSTRAINT FK_D6E3D458F5B7AF75');
        $this->addSql('DROP SEQUENCE tb_wallet_wallet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_address_address_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_wallet');
        $this->addSql('DROP TABLE tb_address');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('DROP INDEX UNIQ_D6E3D458F5B7AF75');
        $this->addSql('ALTER TABLE tb_user DROP address_id');
    }
}
