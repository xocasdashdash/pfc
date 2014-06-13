<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140612145351 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
            
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(name_en NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(name_es NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(status NULL)");        
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT MODIFY (dateProcessed NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT MODIFY (isProcessed NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY MODIFY (publicityStartDate NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_USER MODIFY (ID_USULDAP NULL)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "oracle", "Migration can only be executed safely on 'oracle'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(name_en NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(name_es NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_STATUS MODIFY(status NOT NULL)");        
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT MODIFY (dateProcessed NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ENROLLMENT MODIFY (isProcessed NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_ACTIVITY MODIFY (publicityStartDate NOT NULL)");
        $this->addSql("ALTER TABLE UAH_GAT_USER MODIFY (ID_USULDAP NOT NULL)");
    }
}
