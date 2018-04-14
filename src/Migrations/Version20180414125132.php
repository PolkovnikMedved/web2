<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180414125132 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE T_MESSAGE (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, fk_author INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE T_PRODUCT (id INT AUTO_INCREMENT NOT NULL, fk_category INT DEFAULT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, price NUMERIC(10, 0) NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, fk_owner INT DEFAULT NULL, INDEX IDX_34D2C07734645A1F (fk_category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE T_COMMENT (id INT AUTO_INCREMENT NOT NULL, fk_article INT DEFAULT NULL, content VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, fk_author INT DEFAULT NULL, INDEX IDX_73EC96B6F215334A (fk_article), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE T_CATEGORY (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE T_ARTICLE (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, fk_author INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE T_PRODUCT ADD CONSTRAINT FK_34D2C07734645A1F FOREIGN KEY (fk_category) REFERENCES T_CATEGORY (id)');
        $this->addSql('ALTER TABLE T_COMMENT ADD CONSTRAINT FK_73EC96B6F215334A FOREIGN KEY (fk_article) REFERENCES T_ARTICLE (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE T_PRODUCT DROP FOREIGN KEY FK_34D2C07734645A1F');
        $this->addSql('ALTER TABLE T_COMMENT DROP FOREIGN KEY FK_73EC96B6F215334A');
        $this->addSql('DROP TABLE T_MESSAGE');
        $this->addSql('DROP TABLE T_PRODUCT');
        $this->addSql('DROP TABLE T_COMMENT');
        $this->addSql('DROP TABLE T_CATEGORY');
        $this->addSql('DROP TABLE T_ARTICLE');
    }
}
