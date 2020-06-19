<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619011539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DEFAULT false');
        $this->addSql('ALTER TABLE tb_post ALTER deleted SET DEFAULT false');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted SET DEFAULT false');
        $this->addSql('ALTER TABLE tb_article ALTER deleted SET DEFAULT false');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tb_post ALTER deleted DROP DEFAULT');
        $this->addSql('ALTER TABLE tb_post_comment ALTER deleted DROP DEFAULT');
        $this->addSql('ALTER TABLE tb_article_comment ALTER deleted SET DROP DEFAULT');
        $this->addSql('ALTER TABLE tb_article ALTER deleted DROP DEFAULT');
    }

}
