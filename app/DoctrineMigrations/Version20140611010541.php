<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140611010541 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY (name_en  VARCHAR2(255) DEFAULT NULL, name_es  VARCHAR2(255) DEFAULT NULL, status  VARCHAR2(255) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT MODIFY (dateProcessed  TIMESTAMP(0) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY MODIFY (publicityStartDate  DATE DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_USER MODIFY (ID_USULDAP  VARCHAR2(255) DEFAULT NULL)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Status MODIFY (NAME_ES  VARCHAR2(255) DEFAULT NULL, NAME_EN  VARCHAR2(255) DEFAULT NULL, STATUS  VARCHAR2(255) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_Enrollment MODIFY (DATEPROCESSED  TIMESTAMP(0) NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_Activity MODIFY (PUBLICITYSTARTDATE  TIMESTAMP(0) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_User MODIFY (ID_USULDAP  VARCHAR2(255) DEFAULT NULL)");
    }
}
