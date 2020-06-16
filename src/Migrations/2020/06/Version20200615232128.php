<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615232128 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_article_comment_article_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_subject_subject_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_article_article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_article_comment (article_comment_id INT NOT NULL, owner_id INT NOT NULL, article_id INT NOT NULL, message TEXT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(article_comment_id))');
        $this->addSql('CREATE INDEX IDX_EAC4F1A87E3C61F9 ON tb_article_comment (owner_id)');
        $this->addSql('CREATE INDEX IDX_EAC4F1A87294869C ON tb_article_comment (article_id)');
        $this->addSql('CREATE TABLE tb_subject (subject_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, activated BOOLEAN DEFAULT \'true\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(subject_id))');
        $this->addSql('CREATE TABLE tb_article (article_id INT NOT NULL, owner_id INT NOT NULL, subject_id INT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, content TEXT NOT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(article_id))');
        $this->addSql('CREATE INDEX IDX_7DEDD4C47E3C61F9 ON tb_article (owner_id)');
        $this->addSql('CREATE INDEX IDX_7DEDD4C423EDC87 ON tb_article (subject_id)');
        $this->addSql('ALTER TABLE tb_article_comment ADD CONSTRAINT FK_EAC4F1A87E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article_comment ADD CONSTRAINT FK_EAC4F1A87294869C FOREIGN KEY (article_id) REFERENCES tb_article (article_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article ADD CONSTRAINT FK_7DEDD4C47E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article ADD CONSTRAINT FK_7DEDD4C423EDC87 FOREIGN KEY (subject_id) REFERENCES tb_subject (subject_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tb_article DROP CONSTRAINT FK_7DEDD4C423EDC87');
        $this->addSql('ALTER TABLE tb_article_comment DROP CONSTRAINT FK_EAC4F1A87294869C');
        $this->addSql('DROP SEQUENCE tb_article_comment_article_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_subject_subject_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_article_article_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_article_comment');
        $this->addSql('DROP TABLE tb_subject');
        $this->addSql('DROP TABLE tb_article');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
    }
}
