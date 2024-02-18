<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218180916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'participant table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `participant` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `first_name` VARCHAR(60) NOT NULL,
                `last_name` VARCHAR(60) NOT NULL,
                `email` VARCHAR(60) NOT NULL,
                `phone_number` VARCHAR(10) NOT NULL,
                `event_id` INT NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`event_id`)
                REFERENCES `event` (`id`)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `participant`');
    }
}
