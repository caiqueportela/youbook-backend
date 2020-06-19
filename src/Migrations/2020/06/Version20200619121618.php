<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619121618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tb_rel_article_evaluation (article_id INT NOT NULL, evaluation_id INT NOT NULL, PRIMARY KEY(article_id, evaluation_id))');
        $this->addSql('CREATE INDEX IDX_B095D0617294869C ON tb_rel_article_evaluation (article_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B095D061456C5646 ON tb_rel_article_evaluation (evaluation_id)');
        $this->addSql('ALTER TABLE tb_rel_article_evaluation ADD CONSTRAINT FK_B095D0617294869C FOREIGN KEY (article_id) REFERENCES tb_article (article_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tb_rel_article_evaluation ADD CONSTRAINT FK_B095D061456C5646 FOREIGN KEY (evaluation_id) REFERENCES tb_evaluation (evaluation_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA youbook');
        $this->addSql('DROP TABLE tb_rel_article_evaluation');
    }
}
