<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140621132427 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY ADD (organizer_name VARCHAR2(255))");
        $this->addSql("UPDATE UAH_GAT_ACTIVITY SET ORGANIZER_NAME='Nombre de la Organización'");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY MODIFY (organizer_name NOT NULL)");
        
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Activity DROP (organizer_name)");
    }
}
