<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140617193547 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");

        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY DROP (start_date );
                        ALTER TABLE UAH_GAT_ACTIVITY ADD (start_date TIMESTAMP(0) );
                        UPDATE UAH_GAT_ACTIVITY SET START_DATE=sysdate;
                        ALTER TABLE UAH_GAT_ACTIVITY MODIFY (start_date NOT NULL);");
        $this->addSql("ALTER TABLE UAH_GAT_SESSION MODIFY (session_value  CLOB NOT NULL)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");

        $this->addSql("ALTER TABLE UAH_GAT_Activity DROP (start_date)");
        $this->addSql("ALTER TABLE UAH_GAT_Session MODIFY (SESSION_VALUE  CLOB DEFAULT NULL)");
    }
}
