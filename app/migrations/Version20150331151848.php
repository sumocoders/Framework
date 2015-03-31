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
                username_canonical VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                email_canonical VARCHAR(255) NOT NULL,
                enabled TINYINT(1) NOT NULL,
                salt VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                last_login DATETIME DEFAULT NULL,
                locked TINYINT(1) NOT NULL,
                expired TINYINT(1) NOT NULL,
                expires_at DATETIME DEFAULT NULL,
                confirmation_token VARCHAR(255) DEFAULT NULL,
                password_requested_at DATETIME DEFAULT NULL,
                roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
                credentials_expired TINYINT(1) NOT NULL,
                credentials_expire_at DATETIME DEFAULT NULL,

                UNIQUE INDEX UNIQ_2DA1797792FC23A8 (username_canonical),
                UNIQUE INDEX UNIQ_2DA17977A0D96FBF (email_canonical),

                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
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
