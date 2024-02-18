<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218180711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'event table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `event` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(200) NOT NULL,
                `date` DATETIME NOT NULL,
                `address` VARCHAR(300) NOT NULL,
                PRIMARY KEY (`id`)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `event`');
    }
}
