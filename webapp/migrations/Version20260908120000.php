<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Reconciles the fork's schema with its entity mapping after the rebase onto
 * DOMjudge 9.0.1. Dated after upstream's last migration (Version20250907161952)
 * so it runs last on both a fresh install and an already-migrated database.
 */
final class Version20260908120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align blog_post with its entity mapping after the 9.0.1 rebase';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE blog_post CHANGE blogpostid blogpostid INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT 'Blog post ID'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE blog_post CHANGE blogpostid blogpostid INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT 'Unique ID'");
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
