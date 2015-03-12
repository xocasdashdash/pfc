<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140822203913 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Category DROP INDEX UNIQ_A590610A796A8F92, ADD INDEX IDX_A590610A796A8F92 (parent_category_id)");
        $this->addSql("ALTER TABLE UAH_GAT_Category CHANGE hash_category hash_category VARCHAR(40) NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Category DROP INDEX IDX_A590610A796A8F92, ADD UNIQUE INDEX UNIQ_A590610A796A8F92 (parent_category_id)");
        $this->addSql("ALTER TABLE UAH_GAT_Category CHANGE hash_category hash_category VARCHAR(128) NOT NULL");
    }
}
