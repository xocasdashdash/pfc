<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140602235515 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE UAH_GAT_v_TuibPersonaUser");
        $this->addSql("ALTER TABLE UAH_GAT_Status CHANGE name_es name_es VARCHAR(255) NOT NULL, CHANGE name_en name_en VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_User CHANGE ID_USULDAP ID_USULDAP VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE UAH_GAT_v_TuibPersonaUser (codnum INT AUTO_INCREMENT NOT NULL, nomprs VARCHAR(32) NOT NULL, email VARCHAR(100) DEFAULT NULL, ll1prs VARCHAR(32) DEFAULT NULL, ll2prs VARCHAR(32) DEFAULT NULL, tlfprs VARCHAR(255) NOT NULL, ID_USULDAP VARCHAR(255) NOT NULL, PRIMARY KEY(codnum)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE UAH_GAT_Status CHANGE name_es name_es VARCHAR(255) NOT NULL, CHANGE name_en name_en VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_User CHANGE ID_USULDAP ID_USULDAP VARCHAR(255) NOT NULL");
    }
}
