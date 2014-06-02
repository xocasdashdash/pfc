<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140603000652 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Status CHANGE name_es name_es VARCHAR(255) NOT NULL, CHANGE name_en name_en VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_Activity CHANGE hasAdditionalWorkload hasAdditionalWorkload TINYINT(1) DEFAULT NULL, CHANGE registrationManagement registrationManagement TINYINT(1) DEFAULT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_User CHANGE ID_USULDAP ID_USULDAP VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Activity CHANGE hasAdditionalWorkload hasAdditionalWorkload TINYINT(1) NOT NULL, CHANGE registrationManagement registrationManagement TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_Status CHANGE name_es name_es VARCHAR(255) NOT NULL, CHANGE name_en name_en VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_User CHANGE ID_USULDAP ID_USULDAP VARCHAR(255) NOT NULL");
    }
}
