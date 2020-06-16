<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616011114 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article_comment RENAME COLUMN create_at TO created_at');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT \'false\'');
        $this->addSql('ALTER TABLE tb_article_comment RENAME COLUMN created_at TO create_at');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT \'false\'');
    }
}
