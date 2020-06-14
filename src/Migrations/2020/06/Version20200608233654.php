<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608233654 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_cfa2e632a76ed395');
        $this->addSql('DROP INDEX uniq_cfa2e632d60322ac');
        $this->addSql('CREATE INDEX IDX_CFA2E632A76ED395 ON tb_rel_user_role (user_id)');
        $this->addSql('CREATE INDEX IDX_CFA2E632D60322AC ON tb_rel_user_role (role_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_CFA2E632A76ED395');
        $this->addSql('DROP INDEX IDX_CFA2E632D60322AC');
        $this->addSql('CREATE UNIQUE INDEX uniq_cfa2e632a76ed395 ON tb_rel_user_role (user_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_cfa2e632d60322ac ON tb_rel_user_role (role_id)');
    }
}
