<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218180537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'news table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `news` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(200) NOT NULL,
                `description` LONGTEXT NOT NULL,
                `created_at` DATETIME NOT NULL,
                `picture` VARCHAR(200) NULL,
                PRIMARY KEY (`id`)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `news`');
    }
}
