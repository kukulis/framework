<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122211552_permission_belongs_to_group extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(/** @lang MySQL */ 'CREATE TABLE permissions_belongs_to_group (
                        group_id INT NOT NULL, 
                        permission_id INT NOT NULL, 
                        INDEX IDX_E4A139C5FE54D947 (group_id), 
                        INDEX IDX_E4A139C5FED90CCA (permission_id),
                        PRIMARY KEY(group_id, permission_id)
             ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(/** @lang MySQL */ 'ALTER TABLE permissions_belongs_to_group 
                           ADD CONSTRAINT FK_PERMISSION_GROUP_GROUP FOREIGN KEY (group_id) REFERENCES `groups` (id)'
        );
        $this->addSql(/** @lang MySQL */ 'ALTER TABLE permissions_belongs_to_group 
                           ADD CONSTRAINT FK_PERMISSION_GROUP_PERMISSION FOREIGN KEY (permission_id) REFERENCES permissions (id)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permissions_belongs_to_group DROP FOREIGN KEY FK_PERMISSION_GROUP_GROUP');
        $this->addSql('ALTER TABLE permissions_belongs_to_group DROP FOREIGN KEY FK_PERMISSION_GROUP_PERMISSION');
        $this->addSql('DROP TABLE permissions_belongs_to_group');
    }
}
