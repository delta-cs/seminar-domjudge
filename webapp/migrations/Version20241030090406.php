<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * We used to do manual sql migrations, this migration sync's the current entity status and the current database schema.
 */
final class Version20241030090406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This is a diff migration generated via doctrine:migrations:diff';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rankcache CHANGE points_restricted points_restricted INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'Total correctness points (restricted audience)\', CHANGE points_public points_public INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'Total correctness points (public)\'');
        $this->addSql('ALTER TABLE scorecache CHANGE points_restricted points_restricted DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL COMMENT \'Number of points scored by this team on this problem (restricted audience)\', CHANGE points_public points_public DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL COMMENT \'Number of points scored by this team on this problem (public)\'');
        $this->addSql('ALTER TABLE testcase CHANGE testcasegroupid testcasegroupid INT UNSIGNED DEFAULT NULL COMMENT \'Testcase group ID\'');
        $this->addSql('ALTER TABLE testcase RENAME INDEX testcase_ibfk TO IDX_4C1E5C3930A04A79');
        $this->addSql('ALTER TABLE testcase_group CHANGE testcasegroupid testcasegroupid INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'Testcase group ID\', CHANGE name name VARCHAR(255) DEFAULT NULL COMMENT \'Which part of the problem this group tests.\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rankcache CHANGE points_restricted points_restricted NUMERIC(32, 9) DEFAULT NULL, CHANGE points_public points_public NUMERIC(32, 9) DEFAULT NULL');
        $this->addSql('ALTER TABLE scorecache CHANGE points_restricted points_restricted NUMERIC(32, 9) DEFAULT \'0.000000000\' NOT NULL COMMENT \'Max points achieved (restricted audience)\', CHANGE points_public points_public NUMERIC(32, 9) DEFAULT \'0.000000000\' NOT NULL COMMENT \'Max points achieved (public)\'');
        $this->addSql('ALTER TABLE testcase CHANGE testcasegroupid testcasegroupid INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE testcase RENAME INDEX idx_4c1e5c3930a04a79 TO testcase_ibfk');
        $this->addSql('ALTER TABLE testcase_group CHANGE testcasegroupid testcasegroupid INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'Unique ID\', CHANGE name name VARCHAR(255) DEFAULT NULL COMMENT \'Which part of the problem this group tests\'');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
