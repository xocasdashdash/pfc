<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140628132640 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");

        $this->addSql("ALTER TABLE UAH_GAT_USER DROP CONSTRAINT FK_8D0E506B35C5756");
        $this->addSql("ALTER TABLE UAH_GAT_USER ADD CONSTRAINT FK_8D0E506B35C5756 FOREIGN KEY (degree_id) REFERENCES UAH_GAT_Degree (id) ON DELETE SET NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");

        $this->addSql("ALTER TABLE UAH_GAT_User DROP CONSTRAINT FK_8D0E506B35C5756");
        $this->addSql("ALTER TABLE UAH_GAT_User ADD CONSTRAINT FK_8D0E506B35C5756 FOREIGN KEY (DEGREE_ID) REFERENCES UAH_GAT_DEGREE (ID)");
    }
}
