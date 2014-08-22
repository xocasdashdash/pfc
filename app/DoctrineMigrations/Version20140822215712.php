<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140822215712 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories DROP FOREIGN KEY FK_980DD3D812469DE2");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories DROP FOREIGN KEY FK_980DD3D881C06096");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D812469DE2 FOREIGN KEY (category_id) REFERENCES UAH_GAT_Category (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D881C06096 FOREIGN KEY (activity_id) REFERENCES UAH_GAT_Activity (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories DROP FOREIGN KEY FK_980DD3D881C06096");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories DROP FOREIGN KEY FK_980DD3D812469DE2");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D881C06096 FOREIGN KEY (activity_id) REFERENCES UAH_GAT_Category (id)");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D812469DE2 FOREIGN KEY (category_id) REFERENCES UAH_GAT_Activity (id)");
    }
}
