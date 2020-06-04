<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200604161855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ticket ADD seat_number INT NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ticket DROP seat_number');
    }
}
