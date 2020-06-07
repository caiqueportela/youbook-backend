<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607225243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tb_rel_user_role (user_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(user_id, role_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFA2E632A76ED395 ON tb_rel_user_role (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFA2E632D60322AC ON tb_rel_user_role (role_id)');
        $this->addSql('ALTER TABLE tb_rel_user_role ADD CONSTRAINT FK_CFA2E632A76ED395 FOREIGN KEY (user_id) REFERENCES tb_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_user_role ADD CONSTRAINT FK_CFA2E632D60322AC FOREIGN KEY (role_id) REFERENCES tb_user_role (role_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE tb_rel_user_role');
    }
}
