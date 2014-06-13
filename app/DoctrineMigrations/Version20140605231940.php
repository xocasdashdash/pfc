<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140605231940 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY (name_en  VARCHAR2(255) DEFAULT NULL, name_es  VARCHAR2(255) DEFAULT NULL, status  VARCHAR2(255) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_USER MODIFY (ID_USULDAP  VARCHAR2(255) DEFAULT NULL)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("CREATE TABLE UAH_GAT_MIGRATION_VERSIONS (VERSION VARCHAR2(255) NOT NULL, PRIMARY KEY(VERSION))");
        $this->addSql("ALTER TABLE UAH_GAT_Status MODIFY (NAME_ES  VARCHAR2(255) DEFAULT NULL, NAME_EN  VARCHAR2(255) DEFAULT NULL, STATUS  VARCHAR2(255) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_User MODIFY (ID_USULDAP  VARCHAR2(255) DEFAULT NULL)");
    }
}
