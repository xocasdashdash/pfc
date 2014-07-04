<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140703173315 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Activity CHANGE numberOfPlacesOffered numberOfPlacesOffered INT DEFAULT NULL, CHANGE cost cost DOUBLE PRECISION DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE UAH_GAT_migration_versions (version VARCHAR(255) NOT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE UAH_GAT_Activity CHANGE numberOfPlacesOffered numberOfPlacesOffered INT NOT NULL, CHANGE cost cost DOUBLE PRECISION NOT NULL");
    }
}
