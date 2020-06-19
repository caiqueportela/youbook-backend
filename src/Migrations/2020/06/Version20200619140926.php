<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619140926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tb_course_user DROP CONSTRAINT fk_2c8e5552a76ed395');
        $this->addSql('DROP INDEX idx_2c8e5552a76ed395');
        $this->addSql('ALTER TABLE tb_course_user RENAME COLUMN user_id TO owner_id');
        $this->addSql('ALTER TABLE tb_course_user ADD CONSTRAINT FK_2C8E55527E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2C8E55527E3C61F9 ON tb_course_user (owner_id)');
        $this->addSql('ALTER TABLE tb_donation DROP CONSTRAINT fk_94294bedd5a53a7');
        $this->addSql('DROP INDEX idx_94294bedd5a53a7');
        $this->addSql('ALTER TABLE tb_donation RENAME COLUMN user_donor_id TO donor_id');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT FK_94294BE3DD7B7A7 FOREIGN KEY (donor_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_94294BE3DD7B7A7 ON tb_donation (donor_id)');
        $this->addSql('ALTER TABLE tb_course DROP CONSTRAINT fk_ab4976f5a76ed395');
        $this->addSql('DROP INDEX idx_ab4976f5a76ed395');
        $this->addSql('ALTER TABLE tb_course RENAME COLUMN user_id TO owner_id');
        $this->addSql('ALTER TABLE tb_course ADD CONSTRAINT FK_AB4976F57E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB4976F57E3C61F9 ON tb_course (owner_id)');
        $this->addSql('ALTER TABLE tb_comment DROP CONSTRAINT fk_eba388cea76ed395');
        $this->addSql('DROP INDEX idx_eba388cea76ed395');
        $this->addSql('ALTER TABLE tb_comment RENAME COLUMN user_id TO owner_id');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT FK_EBA388CE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EBA388CE7E3C61F9 ON tb_comment (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('ALTER TABLE tb_donation DROP CONSTRAINT FK_94294BE3DD7B7A7');
        $this->addSql('DROP INDEX IDX_94294BE3DD7B7A7');
        $this->addSql('ALTER TABLE tb_donation RENAME COLUMN donor_id TO user_donor_id');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT fk_94294bedd5a53a7 FOREIGN KEY (user_donor_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_94294bedd5a53a7 ON tb_donation (user_donor_id)');
        $this->addSql('ALTER TABLE tb_course_user DROP CONSTRAINT FK_2C8E55527E3C61F9');
        $this->addSql('DROP INDEX IDX_2C8E55527E3C61F9');
        $this->addSql('ALTER TABLE tb_course_user RENAME COLUMN owner_id TO user_id');
        $this->addSql('ALTER TABLE tb_course_user ADD CONSTRAINT fk_2c8e5552a76ed395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2c8e5552a76ed395 ON tb_course_user (user_id)');
        $this->addSql('ALTER TABLE tb_course DROP CONSTRAINT FK_AB4976F57E3C61F9');
        $this->addSql('DROP INDEX IDX_AB4976F57E3C61F9');
        $this->addSql('ALTER TABLE tb_course RENAME COLUMN owner_id TO user_id');
        $this->addSql('ALTER TABLE tb_course ADD CONSTRAINT fk_ab4976f5a76ed395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ab4976f5a76ed395 ON tb_course (user_id)');
        $this->addSql('ALTER TABLE tb_comment DROP CONSTRAINT FK_EBA388CE7E3C61F9');
        $this->addSql('DROP INDEX IDX_EBA388CE7E3C61F9');
        $this->addSql('ALTER TABLE tb_comment RENAME COLUMN owner_id TO user_id');
        $this->addSql('ALTER TABLE tb_comment ADD CONSTRAINT fk_eba388cea76ed395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_eba388cea76ed395 ON tb_comment (user_id)');
    }
}
