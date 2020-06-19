<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619114123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tb_donation DROP CONSTRAINT fk_94294bea76ed395');
        $this->addSql('DROP INDEX idx_94294bea76ed395');
        $this->addSql('ALTER TABLE tb_donation RENAME COLUMN user_id TO user_donor_id');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT FK_94294BEDD5A53A7 FOREIGN KEY (user_donor_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_94294BEDD5A53A7 ON tb_donation (user_donor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('ALTER TABLE tb_donation DROP CONSTRAINT FK_94294BEDD5A53A7');
        $this->addSql('DROP INDEX IDX_94294BEDD5A53A7');
        $this->addSql('ALTER TABLE tb_donation RENAME COLUMN user_donor_id TO user_id');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT fk_94294bea76ed395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_94294bea76ed395 ON tb_donation (user_id)');
    }
}
