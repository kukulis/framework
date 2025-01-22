<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122212758_user_belongs_to_group extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(/** @lang MySQL */ 'CREATE TABLE user_belongs_to_group (
                user_id INT NOT NULL, 
                group_id INT NOT NULL, 
                INDEX IDX_DA9A6D66A76ED395 (user_id), 
                INDEX IDX_DA9A6D66FE54D947 (group_id), 
                PRIMARY KEY(user_id, group_id)
             ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(/** @lang MySQL */ 'ALTER TABLE user_belongs_to_group ADD CONSTRAINT FK_GROUP_USER_USER FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql(/** @lang MySQL */'ALTER TABLE user_belongs_to_group ADD CONSTRAINT FK_GROUP_USER_GROUP FOREIGN KEY (group_id) REFERENCES `groups` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_belongs_to_group DROP FOREIGN KEY FK_GROUP_USER_USER');
        $this->addSql('ALTER TABLE user_belongs_to_group DROP FOREIGN KEY FK_GROUP_USER_GROUP');
        $this->addSql('DROP TABLE user_belongs_to_group');
    }
}
