<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241003104250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add points_restricted and points_public to scorecache representing max points achieved for a given problem in the contest';
    }

    public function up(Schema $schema): void
    {
        // Add the columns points_restricted and points_public to the scorecache table
        $this->addSql('ALTER TABLE scorecache ADD points_restricted DECIMAL(32,9) DEFAULT 0 NOT NULL COMMENT \'Max points achieved (restricted audience)\'');
        $this->addSql('ALTER TABLE scorecache ADD points_public DECIMAL(32,9) DEFAULT 0 NOT NULL COMMENT \'Max points achieved (public)\'');

        // Change the column type from int unsigned to double in the rankcache table, columns points_public and points_restricted
        $this->addSql('ALTER TABLE rankcache MODIFY points_restricted DECIMAL(32,9)');
        $this->addSql('ALTER TABLE rankcache MODIFY points_public DECIMAL(32,9)');
    }

    public function down(Schema $schema): void
    {
        // Drop the columns points_restricted and points_public from the scorecache table
        $this->addSql('ALTER TABLE scorecache DROP points_restricted');
        $this->addSql('ALTER TABLE scorecache DROP points_public');

        // Change the column type from double to int unsigned in the rankcache table, columns points_public and points_restricted
        $this->addSql('ALTER TABLE rankcache MODIFY points_restricted INT UNSIGNED');
        $this->addSql('ALTER TABLE rankcache MODIFY points_public INT UNSIGNED');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
