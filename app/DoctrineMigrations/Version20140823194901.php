<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140823194901 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Category DROP FOREIGN KEY FK_A590610A796A8F92");
        $this->addSql("ALTER TABLE UAH_GAT_Category ADD CONSTRAINT FK_A590610A796A8F92 FOREIGN KEY (parent_category_id) REFERENCES UAH_GAT_Category (id) ON DELETE SET NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Category DROP FOREIGN KEY FK_A590610A796A8F92");
        $this->addSql("ALTER TABLE UAH_GAT_Category ADD CONSTRAINT FK_A590610A796A8F92 FOREIGN KEY (parent_category_id) REFERENCES UAH_GAT_Category (id)");
    }
}
