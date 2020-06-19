<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619012641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tb_donation_donation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tb_donation (donation_id INT NOT NULL, group_id INT NOT NULL, user_id INT NOT NULL, value NUMERIC(10, 2) NOT NULL, message TEXT DEFAULT NULL, information JSON NOT NULL, PRIMARY KEY(donation_id))');
        $this->addSql('CREATE INDEX IDX_94294BEFE54D947 ON tb_donation (group_id)');
        $this->addSql('CREATE INDEX IDX_94294BEA76ED395 ON tb_donation (user_id)');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT FK_94294BEFE54D947 FOREIGN KEY (group_id) REFERENCES tb_group (group_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_donation ADD CONSTRAINT FK_94294BEA76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('DROP SEQUENCE tb_donation_donation_id_seq CASCADE');
        $this->addSql('DROP TABLE tb_donation');
    }
}
