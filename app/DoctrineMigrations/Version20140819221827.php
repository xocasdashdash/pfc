<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140819221827 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE UAH_GAT_Category (id INT AUTO_INCREMENT NOT NULL, status_category INT DEFAULT NULL, parent_category_id INT DEFAULT NULL, name VARCHAR(400) NOT NULL, hash_category VARCHAR(128) NOT NULL, INDEX IDX_A590610A9B96BE19 (status_category), UNIQUE INDEX UNIQ_A590610A796A8F92 (parent_category_id), UNIQUE INDEX search_idx (hash_category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE UAH_GAT_Activities_Categories (activity_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_980DD3D881C06096 (activity_id), INDEX IDX_980DD3D812469DE2 (category_id), PRIMARY KEY(activity_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE UAH_GAT_Category ADD CONSTRAINT FK_A590610A9B96BE19 FOREIGN KEY (status_category) REFERENCES UAH_GAT_Status (id)");
        $this->addSql("ALTER TABLE UAH_GAT_Category ADD CONSTRAINT FK_A590610A796A8F92 FOREIGN KEY (parent_category_id) REFERENCES UAH_GAT_Category (id)");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D881C06096 FOREIGN KEY (activity_id) REFERENCES UAH_GAT_Category (id) ON DELETE RESTRICT");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories ADD CONSTRAINT FK_980DD3D812469DE2 FOREIGN KEY (category_id) REFERENCES UAH_GAT_Activity (id) ON DELETE RESTRICT");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE UAH_GAT_Category DROP FOREIGN KEY FK_A590610A796A8F92");
        $this->addSql("ALTER TABLE UAH_GAT_Activities_Categories DROP FOREIGN KEY FK_980DD3D881C06096");
        $this->addSql("DROP TABLE UAH_GAT_Category");
        $this->addSql("DROP TABLE UAH_GAT_Activities_Categories");
    }
}
