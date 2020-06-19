<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619160151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE youbook.tb_post_comment_post_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE youbook.tb_article_comment_article_comment_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_post_comment');
        $this->addSql('DROP TABLE tb_article_comment');
        $this->addSql('DROP TABLE tb_rel_activity_comment');
        $this->addSql('ALTER TABLE tb_comment ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tb_comment ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tb_comment ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT FK_EBA388CE7294869C FOREIGN KEY (article_id) REFERENCES tb_article (article_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT FK_EBA388CE81C06096 FOREIGN KEY (activity_id) REFERENCES tb_course_activity (activity_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT FK_EBA388CE4B89032C FOREIGN KEY (post_id) REFERENCES tb_post (post_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EBA388CE7294869C ON tb_comment (article_id)');
        $this->addSql('CREATE INDEX IDX_EBA388CE81C06096 ON tb_comment (activity_id)');
        $this->addSql('CREATE INDEX IDX_EBA388CE4B89032C ON tb_comment (post_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('CREATE SEQUENCE youbook.tb_post_comment_post_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE youbook.tb_article_comment_article_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_post_comment (post_comment_id INT NOT NULL, owner_id INT NOT NULL, post_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(post_comment_id))');
        $this->addSql('CREATE INDEX idx_caf336d87e3c61f9 ON tb_post_comment (owner_id)');
        $this->addSql('CREATE INDEX idx_caf336d84b89032c ON tb_post_comment (post_id)');
        $this->addSql('CREATE TABLE tb_article_comment (article_comment_id INT NOT NULL, owner_id INT NOT NULL, article_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(article_comment_id))');
        $this->addSql('CREATE INDEX idx_eac4f1a87294869c ON tb_article_comment (article_id)');
        $this->addSql('CREATE INDEX idx_eac4f1a87e3c61f9 ON tb_article_comment (owner_id)');
        $this->addSql('CREATE TABLE tb_rel_activity_comment (activity_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(activity_id, comment_id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_b4009569f8697d13 ON tb_rel_activity_comment (comment_id)');
        $this->addSql('CREATE INDEX idx_b400956981c06096 ON tb_rel_activity_comment (activity_id)');
        $this->addSql('ALTER TABLE tb_post_comment ADD CONSTRAINT fk_caf336d87e3c61f9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_post_comment ADD CONSTRAINT fk_caf336d84b89032c FOREIGN KEY (post_id) REFERENCES tb_post (post_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article_comment ADD CONSTRAINT fk_eac4f1a87e3c61f9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_article_comment ADD CONSTRAINT fk_eac4f1a87294869c FOREIGN KEY (article_id) REFERENCES tb_article (article_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_activity_comment ADD CONSTRAINT fk_b400956981c06096 FOREIGN KEY (activity_id) REFERENCES tb_course_activity (activity_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_activity_comment ADD CONSTRAINT fk_b4009569f8697d13 FOREIGN KEY (comment_id) REFERENCES tb_comment (comment_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_comment DROP CONSTRAINT FK_EBA388CE7294869C');
        $this->addSql('ALTER TABLE tb_comment DROP CONSTRAINT FK_EBA388CE81C06096');
        $this->addSql('ALTER TABLE tb_comment DROP CONSTRAINT FK_EBA388CE4B89032C');
        $this->addSql('DROP INDEX IDX_EBA388CE7294869C');
        $this->addSql('DROP INDEX IDX_EBA388CE81C06096');
        $this->addSql('DROP INDEX IDX_EBA388CE4B89032C');
        $this->addSql('ALTER TABLE tb_comment DROP article_id');
        $this->addSql('ALTER TABLE tb_comment DROP activity_id');
        $this->addSql('ALTER TABLE tb_comment DROP post_id');
    }
}
