<?php

namespace SumoCodersFramework\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171012095221 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE BaseUser (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, displayName VARCHAR(255) NOT NULL, passwordResetToken VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL COMMENT \'(DC2Type:user_status)\', discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE User');
        $this->addSql('CREATE TABLE User (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977BF396750 FOREIGN KEY (id) REFERENCES BaseUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF2272BF396750 FOREIGN KEY (id) REFERENCES BaseUser (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_A3368EB3BF396750');
        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF2272BF396750');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Admin');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, salt VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, displayName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, passwordResetToken VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, status VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:user_status)\', discr VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE BaseUser');
    }
}
