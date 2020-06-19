<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619114705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tb_article ADD group_id INT NOT NULL');
        $this->addSql('ALTER TABLE tb_article ADD CONSTRAINT FK_7DEDD4C4FE54D947 FOREIGN KEY (group_id) REFERENCES tb_group (group_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7DEDD4C4FE54D947 ON tb_article (group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tb_article DROP CONSTRAINT FK_7DEDD4C4FE54D947');
        $this->addSql('DROP INDEX IDX_7DEDD4C4FE54D947');
        $this->addSql('ALTER TABLE tb_article DROP group_id');
    }
}
