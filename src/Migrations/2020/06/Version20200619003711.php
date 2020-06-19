<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619003711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE tb_user_role_role_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tb_group_role_group_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_user_role_user_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_group_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_group_user_group_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_group_role (group_role_id INT NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(group_role_id))');
        $this->addSql('CREATE TABLE tb_group (group_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, activated BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(group_id))');
        $this->addSql('CREATE TABLE tb_group_user (group_user_id INT NOT NULL, user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(group_user_id))');
        $this->addSql('CREATE INDEX IDX_1D556EAAA76ED395 ON tb_group_user (user_id)');
        $this->addSql('CREATE INDEX IDX_1D556EAAFE54D947 ON tb_group_user (group_id)');
        $this->addSql('CREATE TABLE tb_rel_user_group_role (group_user_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(group_user_id, role_id))');
        $this->addSql('CREATE INDEX IDX_90604183216E8799 ON tb_rel_user_group_role (group_user_id)');
        $this->addSql('CREATE INDEX IDX_90604183D60322AC ON tb_rel_user_group_role (role_id)');
        $this->addSql('ALTER TABLE tb_group_user ADD CONSTRAINT FK_1D556EAAA76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_group_user ADD CONSTRAINT FK_1D556EAAFE54D947 FOREIGN KEY (group_id) REFERENCES tb_group (group_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_user_group_role ADD CONSTRAINT FK_90604183216E8799 FOREIGN KEY (group_user_id) REFERENCES tb_group_user (group_user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_user_group_role ADD CONSTRAINT FK_90604183D60322AC FOREIGN KEY (role_id) REFERENCES tb_group_role (group_role_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_user_role RENAME COLUMN role_id TO user_role_id');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_rel_user_role DROP CONSTRAINT FK_CFA2E632D60322AC');
        $this->addSql('ALTER TABLE tb_rel_user_role ADD CONSTRAINT FK_CFA2E632D60322AC FOREIGN KEY (role_id) REFERENCES tb_user_role (user_role_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tb_rel_user_group_role DROP CONSTRAINT FK_90604183D60322AC');
        $this->addSql('ALTER TABLE tb_group_user DROP CONSTRAINT FK_1D556EAAFE54D947');
        $this->addSql('ALTER TABLE tb_rel_user_group_role DROP CONSTRAINT FK_90604183216E8799');
        $this->addSql('DROP SEQUENCE tb_group_role_group_role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_user_role_user_role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_group_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_group_user_group_user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE tb_user_role_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE tb_group_role');
        $this->addSql('DROP TABLE tb_group');
        $this->addSql('DROP TABLE tb_group_user');
        $this->addSql('DROP TABLE tb_rel_user_group_role');
        $this->addSql('ALTER TABLE tb_rel_user_role DROP CONSTRAINT fk_cfa2e632d60322ac');
        $this->addSql('ALTER TABLE tb_rel_user_role ADD CONSTRAINT fk_cfa2e632d60322ac FOREIGN KEY (role_id) REFERENCES tb_user_role (role_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX tb_user_role_pkey');
        $this->addSql('ALTER TABLE tb_user_role RENAME COLUMN user_role_id TO role_id');
        $this->addSql('ALTER TABLE tb_user_role ADD PRIMARY KEY (role_id)');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
    }
}
