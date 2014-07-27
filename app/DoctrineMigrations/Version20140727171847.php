<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140727171847 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Application ADD verified_by_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE UAH_GAT_Application ADD CONSTRAINT FK_5122C9C269F4B775 FOREIGN KEY (verified_by_id) REFERENCES UAH_GAT_User (id) ON DELETE SET NULL");
        $this->addSql("CREATE INDEX IDX_5122C9C269F4B775 ON UAH_GAT_Application (verified_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Application DROP FOREIGN KEY FK_5122C9C269F4B775");
        $this->addSql("DROP INDEX IDX_5122C9C269F4B775 ON UAH_GAT_Application");
        $this->addSql("ALTER TABLE UAH_GAT_Application DROP verified_by_id");
    }
}
