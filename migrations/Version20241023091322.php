<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023091322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP confirmation_date');
        $this->addSql('ALTER TABLE developper DROP hire_date');
        $this->addSql('ALTER TABLE user ADD date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD confirmation_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE developper ADD hire_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP date');
    }
}
