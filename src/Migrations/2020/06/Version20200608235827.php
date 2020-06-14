<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608235827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE post_post_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tb_post_post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_post (post_id INT NOT NULL, owner_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(post_id))');
        $this->addSql('CREATE INDEX IDX_1FA6E9C7E3C61F9 ON tb_post (owner_id)');
        $this->addSql('ALTER TABLE tb_post ADD CONSTRAINT FK_1FA6E9C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE post');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tb_post_post_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE post_post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE post (post_id INT NOT NULL, owner_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(post_id))');
        $this->addSql('CREATE INDEX idx_5a8a6c8d7e3c61f9 ON post (owner_id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT fk_5a8a6c8d7e3c61f9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE tb_post');
    }
}
