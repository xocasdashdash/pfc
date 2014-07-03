<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140701112737 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT RENAME COLUMN dateprocessed TO dateRecognized");
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT DROP (ISPROCESSED)");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY DROP (APPROVEDBYCOMITEE)");
        $this->addSql("ALTER TABLE UAH_GAT_APPLICATION ADD (status NUMBER(10) DEFAULT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_APPLICATION DROP (ISPROCESSED)");
        $this->addSql("ALTER TABLE UAH_GAT_APPLICATION ADD CONSTRAINT FK_5122C9C27B00651C FOREIGN KEY (status) REFERENCES UAH_GAT_Status (id)");
        $this->addSql("CREATE INDEX IDX_5122C9C27B00651C ON UAH_GAT_APPLICATION (status)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Enrollment ADD (ISPROCESSED NUMBER(1) NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_Enrollment RENAME COLUMN daterecognized TO DATEPROCESSED");
        $this->addSql("ALTER TABLE UAH_GAT_Activity ADD (APPROVEDBYCOMITEE NUMBER(1) NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_Application ADD (ISPROCESSED NUMBER(1) NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_Application DROP (status)");
        $this->addSql("ALTER TABLE UAH_GAT_Application DROP CONSTRAINT FK_5122C9C27B00651C");
        $this->addSql("DROP INDEX IDX_5122C9C27B00651C");
    }
}
