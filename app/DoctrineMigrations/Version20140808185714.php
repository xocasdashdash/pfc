<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140808185714 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Enrollment ADD recognized_by_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_Enrollment ADD CONSTRAINT FK_AD0C83B67C037684 FOREIGN KEY (recognized_by_id) REFERENCES UAH_GAT_User (id) ON DELETE SET NULL");
        $this->addSql("CREATE INDEX IDX_AD0C83B67C037684 ON UAH_GAT_Enrollment (recognized_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Enrollment DROP FOREIGN KEY FK_AD0C83B67C037684");
        $this->addSql("DROP INDEX IDX_AD0C83B67C037684 ON UAH_GAT_Enrollment");
        $this->addSql("ALTER TABLE UAH_GAT_Enrollment DROP recognized_by_id");
    }
}
