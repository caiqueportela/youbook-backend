<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619140405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_course_user_course_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_course_activity_activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_course_course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_comment_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_course_chapter_chapter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tb_course_user_activity_course_user_activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_course_user (course_user_id INT NOT NULL, user_id INT NOT NULL, course_id INT NOT NULL, percentage INT DEFAULT 0 NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, concluded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(course_user_id))');
        $this->addSql('CREATE INDEX IDX_2C8E5552A76ED395 ON tb_course_user (user_id)');
        $this->addSql('CREATE INDEX IDX_2C8E5552591CC992 ON tb_course_user (course_id)');
        $this->addSql('CREATE TABLE tb_course_activity (activity_id INT NOT NULL, chapter_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN NOT NULL, PRIMARY KEY(activity_id))');
        $this->addSql('CREATE INDEX IDX_96A2837F579F4768 ON tb_course_activity (chapter_id)');
        $this->addSql('CREATE TABLE tb_rel_activity_comment (activity_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(activity_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_B400956981C06096 ON tb_rel_activity_comment (activity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4009569F8697D13 ON tb_rel_activity_comment (comment_id)');
        $this->addSql('CREATE TABLE tb_course (course_id INT NOT NULL, subject_id INT NOT NULL, user_id INT NOT NULL, group_id INT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(course_id))');
        $this->addSql('CREATE INDEX IDX_AB4976F523EDC87 ON tb_course (subject_id)');
        $this->addSql('CREATE INDEX IDX_AB4976F5A76ED395 ON tb_course (user_id)');
        $this->addSql('CREATE INDEX IDX_AB4976F5FE54D947 ON tb_course (group_id)');
        $this->addSql('CREATE TABLE tb_rel_course_evaluation (course_id INT NOT NULL, evaluation_id INT NOT NULL, PRIMARY KEY(course_id, evaluation_id))');
        $this->addSql('CREATE INDEX IDX_1C81DBB0591CC992 ON tb_rel_course_evaluation (course_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C81DBB0456C5646 ON tb_rel_course_evaluation (evaluation_id)');
        $this->addSql('CREATE TABLE tb_comment (comment_id INT NOT NULL, user_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(comment_id))');
        $this->addSql('CREATE INDEX IDX_EBA388CEA76ED395 ON tb_comment (user_id)');
        $this->addSql('CREATE TABLE tb_course_chapter (chapter_id INT NOT NULL, course_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(chapter_id))');
        $this->addSql('CREATE INDEX IDX_9A5A5E4F591CC992 ON tb_course_chapter (course_id)');
        $this->addSql('CREATE TABLE tb_course_user_activity (course_user_activity_id INT NOT NULL, course_user_id INT NOT NULL, activity_id INT NOT NULL, viewed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(course_user_activity_id))');
        $this->addSql('CREATE INDEX IDX_6B918C8AAFADD679 ON tb_course_user_activity (course_user_id)');
        $this->addSql('CREATE INDEX IDX_6B918C8A81C06096 ON tb_course_user_activity (activity_id)');
        $this->addSql('ALTER TABLE tb_course_user ADD CONSTRAINT FK_2C8E5552A76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course_user ADD CONSTRAINT FK_2C8E5552591CC992 FOREIGN KEY (course_id) REFERENCES tb_course (course_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course_activity ADD CONSTRAINT FK_96A2837F579F4768 FOREIGN KEY (chapter_id) REFERENCES tb_course_chapter (chapter_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_activity_comment ADD CONSTRAINT FK_B400956981C06096 FOREIGN KEY (activity_id) REFERENCES tb_course_activity (activity_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_activity_comment ADD CONSTRAINT FK_B4009569F8697D13 FOREIGN KEY (comment_id) REFERENCES tb_comment (comment_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course ADD CONSTRAINT FK_AB4976F523EDC87 FOREIGN KEY (subject_id) REFERENCES tb_subject (subject_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course ADD CONSTRAINT FK_AB4976F5A76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course ADD CONSTRAINT FK_AB4976F5FE54D947 FOREIGN KEY (group_id) REFERENCES tb_group (group_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_course_evaluation ADD CONSTRAINT FK_1C81DBB0591CC992 FOREIGN KEY (course_id) REFERENCES tb_course (course_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_course_evaluation ADD CONSTRAINT FK_1C81DBB0456C5646 FOREIGN KEY (evaluation_id) REFERENCES tb_evaluation (evaluation_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT FK_EBA388CEA76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course_chapter ADD CONSTRAINT FK_9A5A5E4F591CC992 FOREIGN KEY (course_id) REFERENCES tb_course (course_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course_user_activity ADD CONSTRAINT FK_6B918C8AAFADD679 FOREIGN KEY (course_user_id) REFERENCES tb_course_user (course_user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_course_user_activity ADD CONSTRAINT FK_6B918C8A81C06096 FOREIGN KEY (activity_id) REFERENCES tb_course_activity (activity_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('ALTER TABLE tb_course_user_activity DROP CONSTRAINT FK_6B918C8AAFADD679');
        $this->addSql('ALTER TABLE tb_rel_activity_comment DROP CONSTRAINT FK_B400956981C06096');
        $this->addSql('ALTER TABLE tb_course_user_activity DROP CONSTRAINT FK_6B918C8A81C06096');
        $this->addSql('ALTER TABLE tb_course_user DROP CONSTRAINT FK_2C8E5552591CC992');
        $this->addSql('ALTER TABLE tb_rel_course_evaluation DROP CONSTRAINT FK_1C81DBB0591CC992');
        $this->addSql('ALTER TABLE tb_course_chapter DROP CONSTRAINT FK_9A5A5E4F591CC992');
        $this->addSql('ALTER TABLE tb_rel_activity_comment DROP CONSTRAINT FK_B4009569F8697D13');
        $this->addSql('ALTER TABLE tb_course_activity DROP CONSTRAINT FK_96A2837F579F4768');
        $this->addSql('DROP SEQUENCE tb_course_user_course_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_course_activity_activity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_course_course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_comment_comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_course_chapter_chapter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tb_course_user_activity_course_user_activity_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_course_user');
        $this->addSql('DROP TABLE tb_course_activity');
        $this->addSql('DROP TABLE tb_rel_activity_comment');
        $this->addSql('DROP TABLE tb_course');
        $this->addSql('DROP TABLE tb_rel_course_evaluation');
        $this->addSql('DROP TABLE tb_comment');
        $this->addSql('DROP TABLE tb_course_chapter');
        $this->addSql('DROP TABLE tb_course_user_activity');
    }
}
