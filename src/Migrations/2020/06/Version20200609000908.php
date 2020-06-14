<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609000908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_post_comment_post_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_post_comment (post_comment_id INT NOT NULL, owner_id INT NOT NULL, post_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(post_comment_id))');
        $this->addSql('CREATE INDEX IDX_CAF336D87E3C61F9 ON tb_post_comment (owner_id)');
        $this->addSql('CREATE INDEX IDX_CAF336D84B89032C ON tb_post_comment (post_id)');
        $this->addSql('ALTER TABLE tb_post_comment ADD CONSTRAINT FK_CAF336D87E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_post_comment ADD CONSTRAINT FK_CAF336D84B89032C FOREIGN KEY (post_id) REFERENCES tb_post (post_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tb_post_comment_post_comment_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_post_comment');
    }
}
