<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420160431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo DROP CONSTRAINT fk_5a0eb6a09d86650f');
        $this->addSql('DROP INDEX idx_5a0eb6a09d86650f');
        $this->addSql('ALTER TABLE todo RENAME COLUMN user_id_id TO assignee_id');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A059EC7D60 FOREIGN KEY (assignee_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A0EB6A059EC7D60 ON todo (assignee_id)');
    }

   
}
