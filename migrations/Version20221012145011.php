<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221012145011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_lines (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_CC9FF86BCFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_lines ADD CONSTRAINT FK_CC9FF86BCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE demand CHANGE deleted deleted INT NOT NULL, CHANGE date_modified date_modified VARCHAR(255) NOT NULL, CHANGE date_created date_created VARCHAR(255) NOT NULL, CHANGE photo photo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_lines DROP FOREIGN KEY FK_CC9FF86BCFFE9AD6');
        $this->addSql('DROP TABLE order_lines');
        $this->addSql('DROP TABLE orders');
        $this->addSql('ALTER TABLE demand CHANGE deleted deleted TINYINT(1) DEFAULT 0 NOT NULL, CHANGE date_created date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
    }
}
