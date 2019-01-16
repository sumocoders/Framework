<?php declare(strict_types=1);

namespace SumoCodersFramework\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116073338 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE OtherChoiceOption (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', category VARCHAR(255) NOT NULL, `label` VARCHAR(255) NOT NULL, locale VARCHAR(2) NOT NULL, INDEX category_index (category), UNIQUE INDEX unique_name_index (category, `label`), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IndexItem (field VARCHAR(255) NOT NULL, objectType VARCHAR(255) NOT NULL, otherId VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, PRIMARY KEY(objectType, otherId, field)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE BaseUser (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, displayName VARCHAR(255) NOT NULL, passwordResetToken VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL COMMENT \'(DC2Type:user_status)\', discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE baseuser_userrole (baseuser_id INT NOT NULL, userrole_id INT NOT NULL, INDEX IDX_F8AD2B6A363DAC7F (baseuser_id), INDEX IDX_F8AD2B6A4A62DE12 (userrole_id), PRIMARY KEY(baseuser_id, userrole_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserRole (id INT AUTO_INCREMENT NOT NULL, roleName VARCHAR(255) NOT NULL, createdOn DATETIME NOT NULL, editedOn DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE baseuser_userrole ADD CONSTRAINT FK_F8AD2B6A363DAC7F FOREIGN KEY (baseuser_id) REFERENCES BaseUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE baseuser_userrole ADD CONSTRAINT FK_F8AD2B6A4A62DE12 FOREIGN KEY (userrole_id) REFERENCES UserRole (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977BF396750 FOREIGN KEY (id) REFERENCES BaseUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF2272BF396750 FOREIGN KEY (id) REFERENCES BaseUser (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE baseuser_userrole DROP FOREIGN KEY FK_F8AD2B6A363DAC7F');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977BF396750');
        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF2272BF396750');
        $this->addSql('ALTER TABLE baseuser_userrole DROP FOREIGN KEY FK_F8AD2B6A4A62DE12');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE OtherChoiceOption');
        $this->addSql('DROP TABLE IndexItem');
        $this->addSql('DROP TABLE BaseUser');
        $this->addSql('DROP TABLE baseuser_userrole');
        $this->addSql('DROP TABLE UserRole');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Admin');
    }
}
