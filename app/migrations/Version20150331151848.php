<?php

namespace SumoCodersFramework\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Initial migration
 * This will set-up the initial database structure
 */
class Version20150331151848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE User (
              id INT AUTO_INCREMENT NOT NULL, 
              username VARCHAR(255) NOT NULL, 
              password VARCHAR(255) NOT NULL, 
              salt VARCHAR(255) NOT NULL, 
              displayName VARCHAR(255) NOT NULL, 
              passwordResetToken VARCHAR(255) DEFAULT NULL, 
              email VARCHAR(255) NOT NULL, 
              status VARCHAR(50) NOT NULL COMMENT \'(DC2Type:user_status)\', 
              discr VARCHAR(255) NOT NULL, 
              PRIMARY KEY(id)
             ) 
             DEFAULT CHARACTER SET utf8 
             COLLATE utf8_unicode_ci ENGINE = InnoDB;'
        );
        $this->addSql(
            'CREATE TABLE IndexItem (
                field VARCHAR(255) NOT NULL,
                objectType VARCHAR(255) NOT NULL,
                otherId VARCHAR(255) NOT NULL,
                value LONGTEXT NOT NULL,

                PRIMARY KEY(objectType, otherId, field)
            )
            DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE IndexItem');
    }
}
