<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122133800_create_users_table extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(/** @lang MySQL */'CREATE TABLE users (
                        id INT AUTO_INCREMENT NOT NULL, 
                        username VARCHAR(32) NOT NULL, 
                        password VARCHAR(64) DEFAULT NULL, 
                        name VARCHAR(255) NOT NULL, 
                        PRIMARY KEY(id)
                  ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(/** @lang MySQL */ 'DROP TABLE users');
    }
}
