<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708005702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise RENAME INDEX idx_aedad51c84e0483b TO IDX_AEDAD51C44004D0');
       // $this->addSql('ALTER TABLE muscle_group DROP body_zone');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise RENAME INDEX idx_aedad51c44004d0 TO IDX_AEDAD51C84E0483B');
       // $this->addSql('ALTER TABLE muscle_group ADD body_zone VARCHAR(255) NOT NULL');
    }
}
